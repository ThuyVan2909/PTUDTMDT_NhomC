<?php include 'partials/announcement-bar.php'; ?>

<?php include 'partials/header.php'; ?>
<?php
// TRANG PRODUCT DETAIL KHI CLICK VAO 1 SẢN PHẨM
session_start();
include 'db.php';
$spu_id = isset($_GET['spu_id']) ? intval($_GET['spu_id']) : 0;
if (!$spu_id) { echo "Product not found"; exit; }

$spu = $conn->query("SELECT * FROM spu WHERE id = $spu_id LIMIT 1")->fetch_assoc();
if (!$spu) { echo "SPU không tồn tại"; exit; }


// ===============================
// GHI LỊCH SỬ SẢN PHẨM ĐÃ XEM
// ===============================
if (isset($_SESSION['user_id'])) {
    $user_id = intval($_SESSION['user_id']);

    // Xóa bản ghi cũ nếu user đã xem sản phẩm này (để tránh trùng)
    $conn->query("DELETE FROM view_history WHERE user_id = $user_id AND spu_id = $spu_id");

    // Thêm bản ghi mới
    $stmt = $conn->prepare("INSERT INTO view_history (user_id, spu_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $spu_id);
    $stmt->execute();
}



// 1. Lấy data sản phẩm
$spu_id = $_GET['spu_id'];

$stmt = $conn->prepare("
    SELECT p.name AS product_name,
           c.id AS category_id,
           c.name AS category_name,
           c.parent_id,
           pc.name AS parent_name
    FROM spu p
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN categories pc ON c.parent_id = pc.id
    WHERE p.id = ?
");
$stmt->bind_param("i", $spu_id);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_assoc();

$product_name  = $data['product_name'];
$category_id   = $data['category_id'];
$category_name = $data['category_name'];
$parent_name   = $data['parent_name'];
$parent_id     = $data['parent_id'];


// 2. Build breadcrumb
$breadcrumbs = [
    ["label" => "Trang chủ", "url" => "index.php"],
];

// Xác định section dựa theo parent_id (giống cấu trúc của bạn)
$section = match($parent_id) {
    1 => "phone",
    2 => "laptop",
    3 => "watch",
    default => ""
};

// 3. Nếu có danh mục cha (Macbook thuộc Laptop)
// 3. Nếu có danh mục cha (ví dụ Laptop)
if (!empty($parent_id)) {
    $breadcrumbs[] = [
        "label" => $parent_name,
        "url"   => "index.php?section=$section&cat=$parent_id&product_id=$spu_id"
    ];
}

// 4. Danh mục con (ví dụ MacBook)
$breadcrumbs[] = [
    "label" => $category_name,
    "url"   => "index.php?section=$section&cat=$category_id&product_id=$spu_id"
];


// 5. Tên sản phẩm (không có URL)
$breadcrumbs[] = ["label" => $product_name];

include "breadcrumb.php";






// lấy SKU list
$skus = $conn->query("SELECT id, sku_code, price, promo_price, stock FROM sku WHERE spu_id = $spu_id");

// lấy attribute + value
$attr_sql = "
SELECT 
    a.id AS attribute_id, 
    a.name AS attribute_name, 
    av.id AS value_id, 
    av.value
FROM attributes a
JOIN attribute_values av ON av.attribute_id = a.id
JOIN sku_attribute_values sav ON sav.attribute_value_id = av.id
JOIN sku s ON s.id = sav.sku_id
WHERE s.spu_id = $spu_id
GROUP BY a.id, av.id, a.name, av.value
ORDER BY a.id, av.id
";
$res = $conn->query($attr_sql);

$attributes = [];
while ($r = $res->fetch_assoc()) {
    $aid = $r['attribute_id'];
    if (!isset($attributes[$aid])) {
        $attributes[$aid] = [
            'name' => $r['attribute_name'],
            'values' => []
        ];
    }
    $attributes[$aid]['values'][] = [
        'id' => $r['value_id'], 
        'value' => $r['value']
    ];
}

// lấy ảnh default sku
$defaultSku = $conn->query("SELECT id FROM sku WHERE spu_id = $spu_id LIMIT 1")->fetch_assoc();
$defaultSkuId = $defaultSku['id'] ?? 0;

// lấy attribute values của SKU mặc định
$defaultValues = [];
if ($defaultSkuId) {
    $q = $conn->query("
        SELECT attribute_value_id 
        FROM sku_attribute_values 
        WHERE sku_id = $defaultSkuId
    ");
    while ($r = $q->fetch_assoc()) {
        $defaultValues[] = (int)$r['attribute_value_id'];
    }
}


$images = [];
if ($defaultSkuId) {
    $img = $conn->query("
        SELECT image_url FROM sku_images 
        WHERE sku_id=$defaultSkuId 
        ORDER BY is_primary DESC, id ASC
    ");
    while($i = $img->fetch_assoc()) $images[] = $i['image_url'];
}
?>




<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title><?= $spu['name'] ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/header.css">
<link rel="stylesheet" href="assets/css/footer.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
.buy-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

body { font-family: Arial; background: #f7f7f7; margin: 0; }
.container { width: 1200px; margin: auto; padding: 20px; display: flex; gap: 40px; }

/* LEFT — IMAGES */
.images { width: 40%; }
.images img { width: 100%; border-radius: 8px; }

/* RIGHT — INFO */
.info { width: 60%; background: white; padding: 20px; border-radius: 10px; }
.info h1 { margin-top: 0; }

.price-box {
    font-size: 24px; 
    margin: 10px 0 25px 0;
}
.price-old { text-decoration: line-through; color: gray; font-size: 18px; }

/* ATTRIBUTE BUTTONS */
.attr-group { margin-bottom: 25px; }
.attr-group h3 { margin-bottom: 10px; }

.attr-values button {
    padding: 10px 20px;
    border: 1px solid #ccc;
    margin: 5px;
    background: #fff;
    border-radius: 6px;
    cursor: pointer;
}
.attr-values button.active {
    background: #e30019;
    color: white;
    border-color: #e30019;
}

/* BUY BUTTON */
.buy-btn {
    width: 100%;
    padding: 18px;
    background: #e30019;
    color: white;
    text-align: center;
    font-size: 20px;
    border-radius: 8px;
    cursor: pointer;
    border: none;
    margin-top: 20px;
}

/* SPECIFICATIONS TABLE */
.spec-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    background: #fff;
}

.spec-table thead th {
    background: #f5f5f5;
    padding: 12px;
    text-align: left;
    font-weight: bold;
}

.spec-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
}

.spec-name {
    width: 40%;
    color: #555;
}

.spec-value {
    width: 60%;
    font-weight: 500;
}

</style>

</head>
<body>

<div class="container">

    <!-- LEFT COLUMN: IMAGES -->
    <div class="images">

    <?php 
$fixedImages = array_map(function($p) {
    // Nếu DB đã lưu path đầy đủ thì giữ nguyên
    if (str_starts_with($p, '/techzone/')) {
        return $p;
    }
    // Nếu DB chỉ lưu /assets/... thì thêm /techzone vào đầu
    return '/techzone' . $p;
}, $images);
?>


    <img id="mainImg" 
     src="<?= htmlspecialchars($fixedImages[0] ?? '/techzone/assets/images/no-image.png') ?>" 
     alt="main image">

<div style="display:flex; gap:10px; margin-top:15px;">
    <?php foreach (array_slice($fixedImages, 1) as $img): ?>
        <img src="<?= htmlspecialchars($img) ?>" 
             style="width:60px;height:60px;object-fit:cover;border-radius:5px;cursor:pointer"
             onclick="document.getElementById('mainImg').src='<?= htmlspecialchars($img) ?>'">
    <?php endforeach; ?>
</div>

</div>





    <!-- RIGHT COLUMN: PRODUCT INFO -->
    <div class="info">

        <h1><?= $spu['name'] ?></h1>

        <!-- PRICE BOX -->
         <?php
        $firstSku = $conn->query("SELECT price, promo_price FROM sku WHERE spu_id = $spu_id LIMIT 1")->fetch_assoc();
        ?>
        <div class="price-box">
    <span id="promo_price"><?= number_format($firstSku['promo_price']) ?> đ</span><br>
    <span id="normal_price" class="price-old"><?= number_format($firstSku['price']) ?> đ</span>
</div>

        <!-- ATTRIBUTES -->
<?php foreach ($attributes as $attrId => $attr): ?>
    <div class="attr-group">
        <h3><?= $attr['name'] ?></h3>
        <div class="attr-values">
            <?php foreach ($attr['values'] as $v): ?>
                <button 
                    data-attr-id="<?= $attrId ?>" 
                    data-attr-name="<?= htmlspecialchars($attr['name']) ?>"
                    data-value-id="<?= $v['id'] ?>">
                    <?= $v['value'] ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; ?>


        <!-- BUY BUTTONS -->
<div style="display:flex; gap:12px; margin-top:20px;">
    <button class="buy-btn" id="addToCartBtn" style="background:#ff9900;">THÊM VÀO GIỎ</button>
    <button class="buy-btn" id="buyNowBtn">MUA NGAY</button>
</div>

<!-- THÔNG SỐ KỸ THUẬT (SPU) -->
<!-- ========================= -->
<div id="specifications" style="margin-top:40px;"></div>


<!-- FORM BUY NOW -->
<form id="buyNowForm" action="checkout.php" method="POST" style="display:none;">
    <input type="hidden" name="sku_id" id="formSkuId">
    <input type="hidden" name="quantity" value="1">
    <input type="hidden" name="fullname" value="Khách lẻ">
    <input type="hidden" name="phone" value="0000000000">
    <input type="hidden" name="province_id" value="">
    <input type="hidden" name="district_id" value="">
    <input type="hidden" name="street" value="Chưa cung cấp">
    <input type="hidden" name="payment_method" value="cod">
    <input type="hidden" name="shipping_method" value="standard">
</form>




</div>

        <input type="hidden" id="selectedSkuId" value="<?= $defaultSkuId ?>">


    </div>

</div>

<script>
const DEFAULT_SKU_ID = <?= (int)$defaultSkuId ?>;
const TOTAL_ATTRIBUTES = <?= count($attributes) ?>;
const DEFAULT_VALUES = <?= json_encode($defaultValues) ?>;
</script>


<script>



// ID attribute theo DB
const ID_ATTR_CAPACITY = 1; // dung lượng
const ID_ATTR_COLOR    = 2; // màu sắc
const ID_ATTR_CPU      = 3; // CPU
const ID_ATTR_RAM      = 4; // RAM
const ID_ATTR_SSD      = 5; // SSD

let selectedValues = {}; // attribute_id → value_id
function isFullySelected() {
    return Object.keys(selectedValues).length === TOTAL_ATTRIBUTES;
}

function updateBuyButtons() {
    const addBtn = document.getElementById("addToCartBtn");
    const buyBtn = document.getElementById("buyNowBtn");

    if (isFullySelected()) {
        addBtn.classList.remove("disabled");
        buyBtn.classList.remove("disabled");
    } else {
        addBtn.classList.add("disabled");
        buyBtn.classList.add("disabled");
    }
}


document.querySelectorAll(".attr-values button").forEach(btn => {
    btn.addEventListener("click", () => {
        // active button trong nhóm
        const group = btn.parentNode;
        group.querySelectorAll("button").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");

        const attrId = parseInt(btn.dataset.attrId);
        const valueId = parseInt(btn.dataset.valueId);

        // lưu lựa chọn
        selectedValues[attrId] = valueId;
        updateBuyButtons();


        // nếu chọn MÀU → đổi ảnh
        if (attrId === ID_ATTR_COLOR) {
            fetch("get_sku_images.php?spu_id=<?= $spu_id ?>&color_value_id=" + valueId)
                .then(r => r.json())
                .then(imgs => {
                    if (imgs.length > 0) {
                        document.getElementById("mainImg").src = imgs[0];
                    }
                });
        }

        // tìm SKU đúng với bộ thuộc tính đã chọn → trả về sku_id, price, promo_price, image
        fetch("fetch_sku.php", {
            method: 'POST',
            headers: { 'Content-Type':'application/json' },
            body: JSON.stringify({ spu_id: <?= $spu_id ?>, values: selectedValues })
        })
        .then(r => r.json())
        .then(data => {
            if (data && data.sku_id) {
                // update hidden input SKU
                document.getElementById("selectedSkuId").value = data.sku_id;

                // update giá
                if (data.price) document.getElementById("normal_price").innerText = new Intl.NumberFormat().format(data.price) + " đ";
                if (data.promo_price) document.getElementById("promo_price").innerText = new Intl.NumberFormat().format(data.promo_price) + " đ";

                // update ảnh
                if (data.image) document.getElementById("mainImg").src = data.image;
            }
        });
    });
});


// ADD TO CART
document.getElementById("addToCartBtn").addEventListener("click", () => {
        if (!isFullySelected()) {
        alert("Vui lòng chọn tất cả thuộc tính sản phẩm");
        return;
    }
    const selectedSkuId = document.getElementById("selectedSkuId").value;
    if (!selectedSkuId) {
        alert("Vui lòng chọn đầy đủ thuộc tính");
        return;
    }

    fetch("add_to_cart.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "sku_id=" + selectedSkuId + "&quantity=1"
    })
    .then(r => r.text())
    .then(msg => alert(msg));
});

// BUY NOW
document.getElementById("buyNowBtn").addEventListener("click", () => {
        if (!isFullySelected()) {
        alert("Vui lòng chọn phiên bản");
        return;
    }
    const skuId = document.getElementById("selectedSkuId").value;
    document.getElementById("formSkuId").value = skuId;

    fetch("add_to_cart.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "sku_id=" + skuId + "&quantity=1"
    }).then(() => document.getElementById("buyNowForm").submit());
});

updateBuyButtons();
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    fetch("get_spu_specifications.php?spu_id=<?= $spu_id ?>")
        .then(res => res.json())
        .then(data => {
            if (!Array.isArray(data) || data.length === 0) return;

            let html = `
                <h3>Thông số kỹ thuật</h3>
                <table class="spec-table">
                    <tbody>
            `;

            data.forEach(item => {
                html += `
                    <tr>
                        <td class="spec-name">${item.spec_name}</td>
                        <td class="spec-value">${item.spec_value}</td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                </table>
            `;

            document.getElementById("specifications").innerHTML = html;
        })
        .catch(err => console.error("SPEC ERROR:", err));
});
</script>


<?php include 'partials/footer.php'; ?>

</body>
</html>
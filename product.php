<?php include 'partials/announcement-bar.php'; ?>
<?php include 'partials/header.php'; ?>
<?php
// TRANG PRODUCT DETAIL KHI CLICK VAO 1 SẢN PHẨM

include 'db.php';
$spu_id = isset($_GET['spu_id']) ? intval($_GET['spu_id']) : 0;
if (!$spu_id) { echo "Product not found"; exit; }

$spu = $conn->query("SELECT * FROM spu WHERE id = $spu_id LIMIT 1")->fetch_assoc();
if (!$spu) { echo "SPU không tồn tại"; exit; }
$canReview = false;

if (isset($_SESSION['user_id'])) {
    $uid = (int)$_SESSION['user_id'];

    $stmt = $conn->prepare("
        SELECT 1
        FROM orders o
        JOIN order_items oi ON oi.order_id = o.id
        JOIN sku s ON s.id = oi.sku_id
        WHERE o.user_id = ?
          AND s.spu_id = ?
        LIMIT 1
    ");
    $stmt->bind_param("ii", $uid, $spu_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $canReview = true;
    }
}

// ===========================
// SẢN PHẨM CÓ THỂ BẠN CŨNG THÍCH
// ===========================
$relatedProducts = [];

// Lấy sản phẩm cùng brand trước
$brand_id = $spu['brand_id'] ?? 0;

if ($brand_id) {
    $res = $conn->query("
        SELECT 
            s.id AS spu_id,
            s.name,
            (SELECT image_url FROM sku_images WHERE sku_id=(SELECT id FROM sku WHERE spu_id=s.id LIMIT 1) LIMIT 1) AS image,
            (SELECT price FROM sku WHERE spu_id=s.id LIMIT 1) AS price,
            (SELECT promo_price FROM sku WHERE spu_id=s.id LIMIT 1) AS promo_price,
            (SELECT ROUND(AVG(rating),1) FROM product_reviews WHERE spu_id=s.id) AS avg_rating
        FROM spu s
        WHERE s.brand_id = $brand_id AND s.id != $spu_id
        LIMIT 8
    ");
    while ($r = $res->fetch_assoc()) $relatedProducts[] = $r;
}

// Nếu chưa đủ 8 sản phẩm, lấy thêm theo category
if (count($relatedProducts) < 8) {
    $res = $conn->query("
        SELECT 
            s.id AS spu_id,
            s.name,
            (SELECT image_url FROM sku_images WHERE sku_id=(SELECT id FROM sku WHERE spu_id=s.id LIMIT 1) LIMIT 1) AS image,
            (SELECT price FROM sku WHERE spu_id=s.id LIMIT 1) AS price,
            (SELECT promo_price FROM sku WHERE spu_id=s.id LIMIT 1) AS promo_price,
            (SELECT ROUND(AVG(rating),1) FROM product_reviews WHERE spu_id=s.id) AS avg_rating
        FROM spu s
        WHERE s.category_id = {$spu['category_id']} AND s.id != $spu_id
        LIMIT " . (8 - count($relatedProducts))
    );
    while ($r = $res->fetch_assoc()) $relatedProducts[] = $r;
}


// ===============================
// RATING DATA
// ===============================
$ratingSummary = $conn->query("
    SELECT 
        COUNT(*) AS total_reviews,
        ROUND(AVG(rating), 1) AS avg_rating
    FROM product_reviews
    WHERE spu_id = $spu_id
")->fetch_assoc();

$totalReviews = (int)$ratingSummary['total_reviews'];
$avgRating    = $ratingSummary['avg_rating'] ?? 0;

// distribution 1–5 sao
$ratingDist = [];
$res = $conn->query("
    SELECT rating, COUNT(*) cnt
    FROM product_reviews
    WHERE spu_id = $spu_id
    GROUP BY rating
");
while ($r = $res->fetch_assoc()) {
    $ratingDist[(int)$r['rating']] = (int)$r['cnt'];
}
for ($i = 1; $i <= 5; $i++) {
    if (!isset($ratingDist[$i])) $ratingDist[$i] = 0;
}

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
    background: #d1061eff;
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

:root {
    --primary-blue: #0d6efd;
    --primary-blue-dark: #0b5ed7;
}

/* ========================= */
/* BUY BUTTON                */
/* ========================= */
#buyNowBtn {
    background: var(--primary-blue);
}
#buyNowBtn:hover {
    background: var(--primary-blue-dark);
}

/* ========================= */
/* RATING FULL WIDTH         */
/* ========================= */
.rating-full {
    width: 100%;
    background: #f8f9fa;
    padding: 40px 0;
    margin-top: 50px;
}

.rating-inner {
    width: 1200px;
    margin: auto;
    background: #fff;
    border-radius: 12px;
    padding: 30px;
}

/* TITLE */
.rating-inner h3 {
    color: var(--primary-blue);
    font-weight: 700;
}

/* SUMMARY */
.rating-score {
    font-size: 48px;
    font-weight: bold;
    color: var(--primary-blue);
}

.rating-sub {
    color: #555;
}

/* DISTRIBUTION BAR */
.rating-bar {
    background: #e9ecef;
    height: 8px;
    border-radius: 4px;
    overflow: hidden;
}
.rating-bar-fill {
    height: 100%;
    background: var(--primary-blue);
}

/* STAR COLOR */
.star {
    color: var(--primary-blue);
}

/* REVIEW ITEM */
.review-item {
    padding: 18px 0;
    border-bottom: 1px solid #eee;
}
.review-user {
    font-weight: 600;
}
.review-date {
    color: #999;
    font-size: 13px;
}

/* ADD REVIEW BUTTON */
.add-review-btn {
    background: var(--primary-blue);
    color: #fff;
    border: none;
    padding: 10px 22px;
    border-radius: 6px;
    font-weight: 600;
}
.add-review-btn:hover {
    background: var(--primary-blue-dark);
}

.related-products h3 {
    margin-bottom: 28px;
    font-size: 26px;
    font-weight: 600;
    color: #0d6efd;
    text-align: center;
    position: relative;
}

.related-products h3::after {
    content: "";
    display: block;
    width: 80px;
    height: 3px;
    background: #0d6efd;
    margin: 10px auto 0;
    border-radius: 2px;
}



.related-products-list {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: center;
    
}

.product-card {
    width: 180px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.2);
}

.product-card a {
    text-decoration: none;
    color: #000;
}

.product-img {
    position: relative;
    width: 100%;
    height: 180px;
}

.product-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.discount-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: #e30019;
    color: #fff;
    font-size: 12px;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 4px;
}

.product-info {
    padding: 10px;
    text-align: center;
}

.product-name {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 8px;
    height: 40px;
    overflow: hidden;
}

.product-price {
    font-size: 14px;
    margin-bottom: 6px;
}

.price-sale {
    color: #e30019;
    font-weight: bold;
}

.price-old {
    text-decoration: line-through;
    color: #888;
    margin-left: 6px;
    font-size: 12px;
}

.product-rating {
    color: #f6b01e;
    font-size: 12px;
}

/* ============================= */
/* TECHZONE COMMITMENT CARDS     */
/* ============================= */

.techzone-commitment {
    margin-top: 20px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    font-size: 16px;
}

.commit-card {
    background: #d3d2d2ff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    padding: 10px;
    display: flex;
    gap: 6px;
    align-items: flex-start;
}

.commit-card i {
    color: #0d6efd;
    font-size: 18px;
    margin-top: 2px;
    flex-shrink: 0;
}

.commit-card p {
    margin: 0;
    line-height: 1.4;
    color: #333;
}
.attr-values button.active {
    background: #e30019;
    color: white;
    border-color: #e30019;
    font-weight: 600; /* 👈 IN ĐẬM GIÁ TRỊ ĐANG CHỌN */
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


<!-- TECHZONE COMMITMENT -->
<div class="techzone-commitment">

    <div class="commit-card">
        <i class="bi bi-patch-check-fill"></i>
        <p>
            Máy mới 100%, chính hãng Việt Nam.  
            TechZone là đại lý bán lẻ uỷ quyền chính hãng.
        </p>
    </div>

    <div class="commit-card">
        <i class="bi bi-shield-check"></i>
        <p>
            1 đổi 1 trong 30 ngày nếu lỗi phần cứng.  
            Bảo hành 12 tháng tại TTBH chính hãng.
        </p>
    </div>

    <div class="commit-card">
        <i class="bi bi-box-seam"></i>
        <p>
            Hộp, Sách hướng dẫn,  
            Cây lấy sim, Cáp Type-C đi kèm.
        </p>
    </div>

    <div class="commit-card">
        <i class="bi bi-receipt"></i>
        <p>
            Giá đã bao gồm VAT.  
            Hỗ trợ hoàn thuế VAT (Tax Refund).
        </p>
    </div>

</div>



</div>





    <!-- RIGHT COLUMN: PRODUCT INFO -->
    <div class="info">

        <h1 style="font-weight:700;"><?= htmlspecialchars($spu['name']) ?></h1>
        <!-- PRICE BOX -->
         <?php
        $firstSku = $conn->query("SELECT price, promo_price, stock FROM sku WHERE spu_id = $spu_id LIMIT 1")->fetch_assoc();
        ?>
        <div class="price-box">
        <span id="promo_price"><?= number_format($firstSku['promo_price']) ?> đ</span><br>
        <span id="normal_price" class="price-old"><?= number_format($firstSku['price']) ?> đ</span>
        <div id="stockInfo" class="small mt-1 <?= ($firstSku['stock'] > 0 ? 'text-success' : 'text-danger') ?>">
    <?= $firstSku['stock'] > 0 
        ? 'Còn ' . (int)$firstSku['stock'] . ' sản phẩm'
        : 'Hết hàng'
    ?>


    </div>

</div>

        <!-- ATTRIBUTES -->
<?php foreach ($attributes as $attrId => $attr): ?>
    <div class="attr-group">
        <h3 style="font-weight:500;"><?= htmlspecialchars($attr['name']) ?></h3>
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
    <button class="buy-btn" id="addToCartBtn" style="background:#e30019;">THÊM VÀO GIỎ</button>
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

<?php if (!empty($relatedProducts)): ?>
<div id="relatedProducts" class="related-products">
    <h3>Có thể bạn cũng thích</h3>
    <div class="related-products-list">
        <?php foreach($relatedProducts as $p): ?>
        <?php 
            $discount = ($p['price'] > $p['promo_price'] && $p['promo_price'] > 0)
                        ? round((($p['price'] - $p['promo_price'])/$p['price'])*100)
                        : 0;
        ?>
        <div class="product-card">
            <a href="product.php?spu_id=<?= $p['spu_id'] ?>">
                <div class="product-img">
                    <img src="<?= htmlspecialchars($p['image'] ?? '/techzone/assets/images/no-image.png') ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                    <?php if($discount): ?>
                        <div class="discount-badge">-<?= $discount ?>%</div>
                    <?php endif; ?>
                </div>
                <div class="product-info">
                    <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
                    <div class="product-price">
                        <?php if($p['promo_price'] && $p['promo_price'] < $p['price']): ?>
                            <span class="price-sale"><?= number_format($p['promo_price']) ?> đ</span>
                            <span class="price-old"><?= number_format($p['price']) ?> đ</span>
                        <?php else: ?>
                            <span class="price-sale"><?= number_format($p['price']) ?> đ</span>
                        <?php endif; ?>
                    </div>
                    <div class="product-rating">
                        <?php 
                            $fullStars = floor($p['avg_rating'] ?? 0);
                            $halfStar = ($p['avg_rating'] - $fullStars) >= 0.5 ? 1 : 0;
                        ?>
                        <?php for($i=0;$i<$fullStars;$i++): ?><span>★</span><?php endfor; ?>
                        <?php if($halfStar): ?><span>☆</span><?php endif; ?>
                        <?php for($i=$fullStars+$halfStar;$i<5;$i++): ?><span>☆</span><?php endfor; ?>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>


<!-- =============================== -->
<!-- PRODUCT RATING SECTION         -->
<!-- =============================== -->
<div class="rating-full">
    <div class="rating-inner">
        <h3>Đánh giá <?= htmlspecialchars($spu['name']) ?></h3>

        <!-- SUMMARY -->
        <div style="display:flex; align-items:center; gap:15px; margin:15px 0;">
            <div style="font-size:42px; font-weight:bold;">
                <?= number_format($avgRating,1) ?>
            </div>
            <div>
                <div style="font-size:18px;">/5</div>
                <div style="color:#666;">
                    <?= $totalReviews ?> lượt đánh giá
                </div>
            </div>
        </div>

        <!-- DISTRIBUTION -->
        <?php for ($i = 5; $i >= 1; $i--): ?>
    <?php
        $percent = $totalReviews > 0
            ? round(($ratingDist[$i] / $totalReviews) * 100)
            : 0;
    ?>
    <div style="display:flex; align-items:center; gap:12px; margin:10px 0;">
        <div style="width:50px;"><?= $i ?> ⭐</div>

        <div class="rating-bar" style="flex:1;">
            <div class="rating-bar-fill" style="width:<?= $percent ?>%;"></div>
        </div>

        <div style="width:110px; text-align:right;">
            <?= $ratingDist[$i] ?> đánh giá
        </div>
    </div>
<?php endfor; ?>


        <!-- REVIEW LIST -->
        <div style="margin-top:30px;">
            <div style="display:flex; justify-content:flex-end; margin-bottom:15px;">
    <button class="add-review-btn" id="openReviewModal">
    Thêm đánh giá
</button>

</div>
            <div style="display:flex; gap:10px; margin-bottom:20px;">
    <button class="btn btn-outline-primary filter-star" data-star="0">Tất cả</button>
    <?php for ($i=5;$i>=1;$i--): ?>
        <button class="btn btn-outline-primary filter-star" data-star="<?= $i ?>">
            <?= $i ?> sao
        </button>
    <?php endfor; ?>
</div>

<div id="reviewList"></div>
            <?php
            $reviews = $conn->query("
                SELECT r.*, u.fullname
                FROM product_reviews r
                LEFT JOIN users u ON r.user_id = u.id
                WHERE r.spu_id = $spu_id
                ORDER BY r.created_at DESC
            ");
            ?>

        </div>

    </div>
</div>

<script>
function loadReviews(star = 0) {
    fetch(`get_reviews.php?spu_id=<?= $spu_id ?>&star=${star}`)
        .then(r => r.text())
        .then(html => {
            document.getElementById("reviewList").innerHTML = html;
        });
}

document.querySelectorAll(".filter-star").forEach(btn => {
    btn.addEventListener("click", () => {
        loadReviews(btn.dataset.star);
    });
});

// load mặc định
loadReviews();
</script>


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
function submitReview() {
    fetch("submit_review.php", {
        method: "POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body: new URLSearchParams({
            spu_id: <?= $spu_id ?>,
            rating: document.getElementById("reviewRating").value,
            comment: document.getElementById("reviewComment").value
        })
    })
    .then(r => r.text())
    .then(res => {
        if (res === "OK") {
            alert("Đánh giá đã được gửi");
            location.reload();
        } else if (res === "NOT_LOGIN") {
            alert("Vui lòng đăng nhập");
        } else if (res === "NOT_PURCHASED") {
            alert("Bạn chưa mua sản phẩm này");
        } else {
            alert("Lỗi gửi đánh giá");
        }
    });
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("openReviewModal");
    if (!btn) return;

    btn.addEventListener("click", () => {
        <?php if (!isset($_SESSION['user_id'])): ?>
            alert("Vui lòng đăng nhập để đánh giá");
            return;
        <?php endif; ?>

        <?php if (!$canReview): ?>
            alert("Chỉ khách hàng đã mua sản phẩm mới được đánh giá");
            return;
        <?php endif; ?>

        new bootstrap.Modal(document.getElementById("reviewModal")).show();
    });
});
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


<!-- REVIEW MODAL -->
<div class="modal fade" id="reviewModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Thêm nhận xét</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label>Đánh giá</label>
          <select id="reviewRating" class="form-select">
            <option value="5">★★★★★ - Rất tốt</option>
            <option value="4">★★★★☆ - Tốt</option>
            <option value="3">★★★☆☆ - Bình thường</option>
            <option value="2">★★☆☆☆ - Kém</option>
            <option value="1">★☆☆☆☆ - Rất kém</option>
          </select>
        </div>

        <div class="mb-3">
          <label>Nhận xét</label>
          <textarea id="reviewComment" class="form-control" rows="4"></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <button class="btn btn-primary" onclick="submitReview()">Gửi đánh giá</button>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
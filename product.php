<?php
session_start();
include 'db.php';

$spu_id = isset($_GET['spu_id']) ? intval($_GET['spu_id']) : 0;
if (!$spu_id) { echo "Product not found"; exit; }

$spu = $conn->query("SELECT * FROM spu WHERE id = $spu_id LIMIT 1")->fetch_assoc();
if (!$spu) { echo "SPU không tồn tại"; exit; }

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

<style>
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
</style>

</head>
<body>

<div class="container">

    <!-- LEFT COLUMN: IMAGES -->
    <div class="images">
        <?php foreach ($images as $img): ?>
            <img id="mainImg" src="<?= $img ?>">
            <?php break; ?> 
        <?php endforeach; ?>
    </div>

    <!-- RIGHT COLUMN: PRODUCT INFO -->
    <div class="info">

        <h1><?= $spu['name'] ?></h1>

        <!-- PRICE BOX -->
        <div class="price-box">
            <span id="promo_price">
                <?= number_format($skus->fetch_assoc()['promo_price'] ?? $spu['price']) ?> đ
            </span><br>
        </div>

        <!-- ATTRIBUTES -->
        <?php foreach ($attributes as $attrId => $attr): ?>
            <div class="attr-group">
                <h3><?= $attr['name'] ?></h3>
                <div class="attr-values">
                    <?php foreach ($attr['values'] as $v): ?>
                        <button data-value-id="<?= $v['id'] ?>">
                            <?= $v['value'] ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- BUY BUTTON -->
        <button class="buy-btn">MUA NGAY</button>

    </div>

</div>

<script>
// Kích hoạt nút khi chọn
document.querySelectorAll(".attr-values button").forEach(btn => {
    btn.addEventListener("click", () => {
        let container = btn.parentNode;
        container.querySelectorAll("button").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
    });
});
</script>

</body>
</html>

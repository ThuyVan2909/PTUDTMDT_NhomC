<?php
include "../db.php";

if (!isset($_GET['id'])) {
    die("Thiếu ID sản phẩm");
}

$spu_id = intval($_GET['id']);

// Lấy SPU
$spu = $conn->query("SELECT * FROM spu WHERE id=$spu_id")->fetch_assoc();
if (!$spu) die("Không tìm thấy sản phẩm");

// Lấy các SKU con
$skus = $conn->query("
    SELECT s.*, 
        (SELECT image_url FROM sku_images WHERE sku_id = s.id AND is_primary = 1 LIMIT 1) AS image
    FROM sku s
    WHERE s.spu_id = $spu_id
");

// Lấy attributes
$attrs = $conn->query("
SELECT 
    a.name AS attribute_name,
    av.value AS attribute_value,
    s.sku_code
FROM attributes a
JOIN attribute_values av ON av.attribute_id = a.id
JOIN sku_attribute_values sav ON sav.attribute_value_id = av.id
JOIN sku s ON s.id = sav.sku_id
WHERE s.spu_id = $spu_id
ORDER BY a.id
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Chi tiết sản phẩm</title>
<style>
body { font-family: Arial; padding: 20px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 20px; background: #fff }
th, td { border: 1px solid #ddd; padding: 10px; }
th { background: #135071; color: #fff; }
img { width: 80px; height: 80px; object-fit: cover; border-radius: 6px; }
</style>
</head>

<body>

<h2>Chi tiết sản phẩm</h2>
<h3><?= $spu['brand'] . " " . $spu['name'] ?></h3>
<p><b>Mô tả:</b> <?= $spu['description'] ?></p>

<hr>

<h3>Danh sách SKU</h3>

<table>
<tr>
    <th>Ảnh</th>
    <th>SKU</th>
    <th>Giá</th>
    <th>Giá khuyến mãi</th>
    <th>Tồn kho</th>
</tr>

<?php while ($sku = $skus->fetch_assoc()): ?>
<tr>
    <td><img src="<?= $sku['image'] ?: '../assets/images/no-image.png' ?>"></td>
    <td><?= $sku['sku_code'] ?></td>
    <td><?= number_format($sku['price']) ?>₫</td>
    <td><?= number_format($sku['promo_price']) ?>₫</td>
    <td><?= $sku['stock'] ?></td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>

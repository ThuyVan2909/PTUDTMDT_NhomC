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
body { 
    font-family: Arial; 
    padding: 20px; 
    background: #f4f4f4;
}

.card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 25px;
}

.card h2 { margin-top: 0; }

.btn-edit {
    display: inline-block;
    padding: 10px 18px;
    background: #135071;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    margin-top: 10px;
}
.btn-edit:hover {
    background: #0d3a54;
}

table {
    width: 100%; 
    border-collapse: collapse; 
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
}
th, td { 
    border: 1px solid #ddd; 
    padding: 12px; 
}
th { 
    background: #135071; 
    color: #fff; 
}
img {
    width: 80px; 
    height: 80px; 
    object-fit: cover; 
    border-radius: 8px;
}
</style>
</head>

<body>

<div class="card">
    <h2>Chi tiết sản phẩm</h2>
    <h3><?= $spu['brand'] . " " . $spu['name'] ?></h3>
    <p><b>Mô tả:</b> <?= $spu['description'] ?></p>

    <a class="btn-edit" href="product_edit.php?id=<?= $spu['id'] ?>">Chỉnh sửa sản phẩm</a>
</div>


<div class="card">
    <h3>Danh sách SKU</h3>

<table>
<tr>
    <th>Ảnh</th>
    <th>SKU Code</th>
    <th>Giá</th>
    <th>Giá KM</th>
    <th>Tồn kho</th>
</tr>

<?php while ($sku = $skus->fetch_assoc()): ?>
<tr>
    <td>
        <img src="<?= $sku['image'] ?: '../assets/images/no-image.png' ?>">
    </td>
    <td><?= $sku['sku_code'] ?></td>
    <td><?= number_format($sku['price']) ?>₫</td>
    <td><?= number_format($sku['promo_price']) ?>₫</td>
    <td><?= $sku['stock'] ?></td>
</tr>
<?php endwhile; ?>
</table>

</div>

</body>
</html>

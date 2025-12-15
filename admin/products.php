<?php
include "../db.php";  // đúng đường dẫn

// Tổng số sản phẩm
$totalProducts = $conn->query("SELECT COUNT(*) AS cnt FROM spu")->fetch_assoc()['cnt'] ?? 0;

// Số lượng sản phẩm theo danh mục Laptop / Điện thoại
$prodByCategory = $conn->query("
    SELECT 
        c.id AS parent_id,
        c.name AS category_name,
        COUNT(sp.id) AS total
    FROM categories c
    LEFT JOIN categories sub ON sub.parent_id = c.id OR sub.id = c.id
    LEFT JOIN spu sp ON sp.category_id = sub.id
    WHERE c.parent_id IS NULL  -- chỉ lấy danh mục gốc
    GROUP BY c.id
")->fetch_all(MYSQLI_ASSOC);


// Lấy toàn bộ SPU
$spus = $conn->query("SELECT * FROM spu");
?>

<!DOCTYPE html>
<html>
<head>
<title>Danh sách sản phẩm</title>
<style>
body { font-family: Arial; padding: 20px; }
.card { background: #fff; padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
table { border-collapse: collapse; width: 100%; margin-top: 20px; }
th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
img { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; }
</style>
</head>
<body>

<h2>Danh sách sản phẩm</h2>

<div class="card">
    <h3>Tổng quan sản phẩm</h3>
    <p><b>Tổng số sản phẩm đăng bán:</b> <?= $totalProducts ?></p>
    <ul>
        <?php foreach($prodByCategory as $cat): ?>
            <li><b><?= htmlspecialchars($cat['category_name']) ?>:</b> <?= $cat['total'] ?> sản phẩm</li>
        <?php endforeach; ?>
    </ul>
</div>

<table>
    <tr>
        <th>Ảnh</th>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Mô tả</th>
        <th>Hành động</th>
    </tr>

<?php while($spu = $spus->fetch_assoc()): 
    $imgRow = $conn->query("
        SELECT image_url 
        FROM sku_images 
        WHERE sku_id = (SELECT id FROM sku WHERE spu_id = {$spu['id']} LIMIT 1)
        LIMIT 1
    ")->fetch_assoc();
    $img = $imgRow['image_url'] ?: '../assets/images/no-image.png';
?>
    <tr>
        <td><img src="<?= $img ?>"></td>
        <td><?= $spu["id"] ?></td>
        <td><?= $spu["brand"] . " " . $spu["name"] ?></td>
        <td><?= $spu["description"] ?></td>
        <td><a href="product_detail.php?id=<?= $spu['id'] ?>">Xem chi tiết</a></td>
    </tr>   
<?php endwhile; ?>
</table>

</body>
</html>

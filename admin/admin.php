<?php
session_start();
include ("../db.php");

// CHỐNG TRUY CẬP TRÁI PHÉP
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$sql = "
SELECT 
    spu.id AS spu_id,
    spu.name,
    spu.brand,
    sku.price,
    sku.stock,
    (SELECT image_url FROM sku_images WHERE sku_id = sku.id AND is_primary = 1 LIMIT 1) AS image
FROM spu
LEFT JOIN sku ON sku.spu_id = spu.id
GROUP BY spu.id
ORDER BY spu.created_at DESC
";

$products = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Trang Admin</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; }
        .container { width: 90%; margin: 40px auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        table th {
            background: #135071;
            color: #fff;
        }
        .btn {
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            color: #fff;
        }
        .btn-add { background: #28a745; }
        .btn-edit { background: #ffc107; color: #000; }
        .btn-delete { background: #dc3545; }
        img { width: 70px; height: 70px; object-fit: cover; }
    </style>
</head>

<body>

<div class="container">

    <h2>Quản lý sản phẩm</h2>
    <a href="add_product.php" class="btn btn-add">➕ Thêm sản phẩm</a>
    <br><br>

    <table>
        <tr>
            <th>Ảnh</th>
            <th>Tên SP</th>
            <th>Brand</th>
            <th>Giá</th>
            <th>Tồn kho</th>
            <th>Hành động</th>
        </tr>

        <?php while ($p = $products->fetch_assoc()): ?>
        <tr>
            <td><img src="<?= $p['image'] ?? 'assets/images/no-image.png' ?>"></td>
            <td><?= $p['brand'] . ' ' . $p['name'] ?></td>
            <td><?= $p['brand'] ?></td>
            <td><?= number_format($p['price']) ?>₫</td>
            <td><?= $p['stock'] ?></td>
            <td>
                <a href="edit_product.php?id=<?= $p['spu_id'] ?>" class="btn btn-edit">Sửa</a>
                <a onclick="return confirm('Xóa sản phẩm?')" 
                   href="delete_product.php?id=<?= $p['spu_id'] ?>" class="btn btn-delete">Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>

    </table>

</div>

</body>
</html>

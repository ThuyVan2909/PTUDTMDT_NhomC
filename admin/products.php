<?php
include "../db.php";  // đúng đường dẫn

$spus = $conn->query("SELECT * FROM spu");
?>

<!DOCTYPE html>
<html>
<head>
<title>Danh sách sản phẩm</title>
<style>
table { border-collapse: collapse; width: 100%; }
th, td { padding: 10px; border: 1px solid #ccc; }
</style>
</head>
<body>

<h2>Danh sách sản phẩm</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Mô tả</th>
        <th>Hành động</th>
    </tr>

<?php while($spu = $spus->fetch_assoc()): ?>
    <tr>
        <td><?= $spu["id"] ?></td>
        <td><?= $spu["name"] ?></td>
        <td><?= $spu["description"] ?></td>
        <td><a href="product_detail.php?id=<?= $spu['id'] ?>">Xem</a></td>
    </tr>
<?php endwhile; ?>

</table>

</body>
</html>

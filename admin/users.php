<?php
include "../db.php"; // FIXED: đúng đường dẫn

$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Danh sách người dùng</title>
<style>
table { border-collapse: collapse; width: 100%; }
th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
</style>
</head>
<body>

<h2>Danh sách người dùng</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Họ tên</th>
        <th>Email</th>
        <th>Role</th>
        <th>Ngày tạo</th>
    </tr>

<?php while($u = $users->fetch_assoc()): ?>
    <tr>
        <td><?= $u["id"] ?></td>
        <td><?= $u["fullname"] ?></td>
        <td><?= $u["email"] ?></td>
        <td><?= $u["role"] ?></td>
        <td><?= $u["created_at"] ?></td>
    </tr>
<?php endwhile; ?>

</table>

</body>
</html>

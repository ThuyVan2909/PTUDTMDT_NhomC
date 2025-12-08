<?php
session_start();
include "../db.php";  // đúng đường dẫn

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$view = $_GET['view'] ?? 'dashboard';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial; margin: 0; background: #f4f4f4; }
        .sidebar {
            width: 230px; 
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            background: #135071;
            color: #fff;
        }
        .sidebar h2 { text-align: center; padding: 20px 0; margin: 0; }
        .sidebar a {
            display: block;
            padding: 14px 20px;
            color: #fff;
            text-decoration: none;
        }
        .sidebar a:hover { background: #0d3a54; }

        .content {
            margin-left: 230px;
            padding: 20px;
        }
    </style>
</head>

<body>

<div class="sidebar">
    <h2>ADMIN</h2>
    <a href="admin.php?view=dashboard">🏠 Dashboard</a>
    <a href="admin.php?view=products">📦 Quản lý sản phẩm</a>
    <a href="admin.php?view=orders">📑 Quản lý đơn hàng</a>
    <a href="admin.php?view=users">👤 Người dùng</a>
</div>

<div class="content">

<?php
// Gọi file tương ứng
if ($view === 'products') {
    include "products.php";
} 
else if ($view === 'orders') {
    include "orders.php";
} 
else if ($view === 'users') {
    include "users.php";
} 
else {
    echo "<h2>Chào mừng bạn đến trang quản trị!</h2>";
    echo "Chọn chức năng bên trái để quản lý.";
}
?>

</div>

</body>
</html>

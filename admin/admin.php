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
    <title>Trang quản lý TechZone</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .card {
            background: #fff;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 8px; text-align: left; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>ADMIN</h2>
    <a href="admin.php?view=dashboard">Trang chính</a>
    <a href="admin.php?view=products">Quản lý sản phẩm</a>
    <a href="admin.php?view=orders">Quản lý đơn hàng</a>
    <a href="admin.php?view=users">Quản lý tài khoản</a>
    <a href="admin.php?view=coupons">Quản lý voucher</a>
</div>

<div class="content">

<?php
if ($view === 'products') {
    include "products.php";
} 
else if ($view === 'orders') {
    include "orders.php";
} 
else if ($view === 'users') {
    include "users.php";
} 
else if ($view === 'coupons') {
    include "coupons.php";
}

else {


    // ----------------------


    // ----------------------
    // 2) Doanh thu bán ra theo sản phẩm
    $salesByProduct = $conn->query("
        SELECT sp.name AS product_name, SUM(oi.quantity * COALESCE(oi.price,0) - COALESCE(oi.discount_amount,0)) AS revenue
        FROM order_items oi
        JOIN sku s ON s.id = oi.sku_id
        JOIN spu sp ON sp.id = s.spu_id
        GROUP BY sp.id
    ")->fetch_all(MYSQLI_ASSOC);

    echo "<div class='card'><h3>Doanh thu theo sản phẩm</h3>
    <table><tr><th>Sản phẩm</th><th>Doanh thu (₫)</th></tr>";
    foreach($salesByProduct as $r){
        echo "<tr><td>{$r['product_name']}</td><td>".number_format($r['revenue'])."</td></tr>";
    }
    echo "</table></div>";

    // ----------------------
    // 3) Doanh thu bán ra theo danh mục (bar chart)
    $salesByCat = $conn->query("
        SELECT c.name AS category_name, SUM(oi.quantity * COALESCE(oi.price,0) - COALESCE(oi.discount_amount,0)) AS revenue
        FROM order_items oi
        JOIN sku s ON s.id = oi.sku_id
        JOIN spu sp ON sp.id = s.spu_id
        JOIN categories c ON c.id = sp.category_id
        GROUP BY c.id
    ")->fetch_all(MYSQLI_ASSOC);

    $catNames = json_encode(array_column($salesByCat,'category_name'));
    $catRevenue = json_encode(array_map('floatval', array_column($salesByCat,'revenue')));

    echo "<div class='card'><h3>Doanh thu theo danh mục</h3><canvas id='catChart' height='100'></canvas></div>";

    // ----------------------
    // 4) Sản phẩm bán ra theo ngày (7 ngày gần nhất)
    $salesOverTime = $conn->query("
        SELECT DATE(o.created_at) AS order_date, SUM(oi.quantity) AS sold_qty
        FROM orders o
        JOIN order_items oi ON oi.order_id = o.id
        WHERE o.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY DATE(o.created_at)
        ORDER BY order_date ASC
    ")->fetch_all(MYSQLI_ASSOC);

    $salesDates = json_encode(array_column($salesOverTime,'order_date'));
    $salesQty = json_encode(array_map('intval', array_column($salesOverTime,'sold_qty')));

    echo "<div class='card'><h3>Sản phẩm bán ra trong 7 ngày gần nhất</h3><canvas id='salesChart' height='100'></canvas></div>";

    // ----------------------
    // 5) Số user đăng ký theo ngày (7 ngày gần nhất)
    $usersOverTime = $conn->query("
        SELECT DATE(created_at) AS reg_date, COUNT(*) AS user_count
        FROM users
        WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        GROUP BY DATE(created_at)
        ORDER BY reg_date ASC
    ")->fetch_all(MYSQLI_ASSOC);

    $userDates = json_encode(array_column($usersOverTime,'reg_date'));
    $userCount = json_encode(array_map('intval', array_column($usersOverTime,'user_count')));

    echo "<div class='card'><h3>Số user đăng ký trong 7 ngày gần nhất</h3><canvas id='userChart' height='100'></canvas></div>";
}
?>

</div>

<script>
// Chart doanh thu theo danh mục
new Chart(document.getElementById('catChart'), {
    type: 'bar',
    data: {
        labels: <?= $catNames ?>,
        datasets: [{
            label: 'Doanh thu (₫)',
            data: <?= $catRevenue ?>,
            backgroundColor: 'rgba(54,162,235,0.5)',
            borderColor: 'rgba(54,162,235,1)',
            borderWidth:1
        }]
    },
    options: { responsive:true, scales:{ y:{ beginAtZero:true } } }
});

// Chart sản phẩm bán ra theo thời gian
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: <?= $salesDates ?>,
        datasets: [{
            label: 'Số sản phẩm bán ra',
            data: <?= $salesQty ?>,
            backgroundColor: 'rgba(75,192,192,0.2)',
            borderColor: 'rgba(75,192,192,1)',
            borderWidth:1,
            fill:true
        }]
    },
    options: { responsive:true, scales:{ y:{ beginAtZero:true } } }
});

// Chart số user đăng ký theo thời gian
new Chart(document.getElementById('userChart'), {
    type: 'line',
    data: {
        labels: <?= $userDates ?>,
        datasets: [{
            label: 'Số user đăng ký',
            data: <?= $userCount ?>,
            backgroundColor: 'rgba(255,99,132,0.2)',
            borderColor: 'rgba(255,99,132,1)',
            borderWidth:1,
            fill:true
        }]
    },
    options: { responsive:true, scales:{ y:{ beginAtZero:true } } }
});
</script>

</body>
</html>

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
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { font-family: "Segoe UI"; padding: 20px; font-size: 16px; }
        .sidebar {
            width: 230px; 
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            background: #135071;
            color: #fff;
            line-height: 1.5;
        }
        .sidebar h2 { 
            font-size: 30px; 
            text-align: center; 
            padding: 20px 0; 
            margin: 0;
            line-height: 1.5;
            font-weight: 700;
         }
        .sidebar a {
            position: relative;
            display: flex;
            align-items: center;
            padding: 14px 20px;
            gap: 12px;           
            color: #fff;
            text-decoration: none;
        }
        .sidebar a::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: #f7f0e0ff;        
            opacity: 0;
            transition: opacity 0.2s ease;
        }
.sidebar a:hover::before {
    opacity: 1;
}
.sidebar a.active::before {
    opacity: 1;
}
        .sidebar a i {
            width: 20px;           
            text-align: center;
            font-size: 16px;
        }
        .sidebar a:hover { background: #0d3a54; }
        .sidebar a.active {
            background: #0d3a54;    
            font-weight: 600;
        }
        .sidebar a.active:hover {
            background: #0d3a54;
        }

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


        /* ===== DASHBOARD ===== */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.stat-card {
    background: linear-gradient(135deg, #135071, #1f6fa0);
    color: #fff;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}

.stat-card i {
    font-size: 28px;
    opacity: 0.85;
}

.stat-card h4 {
    margin: 12px 0 6px;
    font-size: 15px;
    font-weight: 500;
    opacity: 0.9;
}

.stat-card h2 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.card-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 12px;
}

/* Table đẹp hơn */
table th {
    background: #f1f5f9;
    font-weight: 600;
}

table tr:hover {
    background: #f9fbfd;
}

/* Chart grid */
.chart-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

/* Doanh thu */
.stat-card:nth-child(1) {
    background: linear-gradient(135deg, #0f2027, #2c5364);
}

/* Đơn hàng */
.stat-card:nth-child(2) {
    background: linear-gradient(135deg, #134e5e, #71b280);
}

/* Người dùng */
.stat-card:nth-child(3) {
    background: linear-gradient(135deg, #41295a, #2f0743);
}

/* Sản phẩm */
.stat-card:nth-child(4) {
    background: linear-gradient(135deg, #f7971e, #ffd200);
    color: #1f2937;
}

.stat-card {
    position: relative;
    overflow: hidden;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}

.stat-card::after {
    content: "";
    position: absolute;
    top: -50%;
    right: -50%;
    width: 120%;
    height: 120%;
    background: radial-gradient(circle, rgba(255,255,255,0.15), transparent 60%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 35px rgba(0,0,0,0.25);
}

.stat-card:hover::after {
    opacity: 1;
}

/* ===== CARD CHART NỔI BẬT ===== */
.chart-card {
    background: linear-gradient(180deg, #ffffff, #f8fafc);
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 15px 30px rgba(0,0,0,0.08);
    border: 1px solid #e5e7eb;
    position: relative;
}

.chart-card::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 5px;
    background: linear-gradient(90deg, #3b82f6, #22d3ee);
    border-radius: 16px 16px 0 0;
}

.chart-title {
    font-size: 17px;
    font-weight: 600;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ===== TABLE DASHBOARD ===== */
.table-dashboard th {
    background: linear-gradient(90deg, #135071, #1f6fa0);
    color: #fff;
    text-transform: uppercase;
    font-size: 13px;
}

.table-dashboard td:last-child {
    font-weight: 600;
    color: #2563eb;
}

.table-dashboard tr {
    transition: background 0.2s ease;
}

.table-dashboard tr:hover {
    background: #eef6ff;
}
    </style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fa-solid fa-user-shield"></i> ADMIN</h2>

    <a href="admin.php?view=dashboard"
       class="<?= $view === 'dashboard' ? 'active' : '' ?>">
        <i class="fa-solid fa-gauge-high"></i>
        Trang chính
    </a>

    <a href="admin.php?view=products"
       class="<?= $view === 'products' ? 'active' : '' ?>">
        <i class="fa-solid fa-box"></i>
        Quản lý sản phẩm
    </a>

    <a href="admin.php?view=orders"
       class="<?= $view === 'orders' ? 'active' : '' ?>">
        <i class="fa-solid fa-receipt"></i>
        Quản lý đơn hàng
    </a>

    <a href="admin.php?view=users"
       class="<?= $view === 'users' ? 'active' : '' ?>">
        <i class="fa-solid fa-users"></i>
        Quản lý tài khoản
    </a>

    <a href="admin.php?view=coupons"
       class="<?= $view === 'coupons' ? 'active' : '' ?>">
        <i class="fa-solid fa-ticket"></i>
        Quản lý voucher
    </a>

    <a href="admin.php?view=banners"
       class="<?= $view === 'banners' ? 'active' : '' ?>">
        <i class="fa-solid fa-image"></i>
        Quản lý banner
    </a>
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
else if ($view === 'banners') {
    include "banner.php";
}
else { 
    
// ===== QUICK STATS =====

// Tổng doanh thu
$totalRevenue = $conn->query("
    SELECT SUM(oi.quantity * COALESCE(oi.price,0) - COALESCE(oi.discount_amount,0)) AS total
    FROM order_items oi
")->fetch_assoc()['total'] ?? 0;

// Tổng đơn hàng
$totalOrders = $conn->query("
    SELECT COUNT(*) AS total FROM orders
")->fetch_assoc()['total'] ?? 0;

// Tổng user
$totalUsers = $conn->query("
    SELECT COUNT(*) AS total FROM users
")->fetch_assoc()['total'] ?? 0;

// Tổng sản phẩm (SPU)
$totalProducts = $conn->query("
    SELECT COUNT(*) AS total FROM spu
")->fetch_assoc()['total'] ?? 0;

echo "
<div class='dashboard-grid'>
    <div class='stat-card'>
        <i class='fa-solid fa-coins'></i>
        <h4>Tổng doanh thu</h4>
        <h2>".number_format($totalRevenue)." ₫</h2>
    </div>

    <div class='stat-card'>
        <i class='fa-solid fa-receipt'></i>
        <h4>Tổng đơn hàng</h4>
        <h2>$totalOrders</h2>
    </div>

    <div class='stat-card'>
        <i class='fa-solid fa-users'></i>
        <h4>Tổng người dùng</h4>
        <h2>$totalUsers</h2>
    </div>

    <div class='stat-card'>
        <i class='fa-solid fa-box'></i>
        <h4>Tổng sản phẩm</h4>
        <h2>$totalProducts</h2>
    </div>
</div>
";

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

   echo "
<div class='chart-card'>
    <div class='chart-title'>
        <i class='fa-solid fa-box'></i>
        Doanh thu theo sản phẩm
    </div>
    <table class='table-dashboard'>
        <tr>
            <th>Sản phẩm</th>
            <th>Doanh thu (₫)</th>
        </tr>";
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

    echo "
<div class='chart-card'>
    <div class='chart-title'>
        <i class='fa-solid fa-chart-column'></i>
        Doanh thu theo danh mục
    </div>
    <canvas id='catChart' height='100'></canvas>
</div>
";

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

    echo "
        <div class='chart-card'>
            <div class='chart-title'>
                <i class='fa-solid fa-chart-line'></i>
                Sản phẩm bán ra (7 ngày)
            </div>
            <canvas id='salesChart' height='100'></canvas>
        </div>
        ";

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

    echo "
        <div class='chart-card'>
            <div class='chart-title'>
                <i class='fa-solid fa-user-plus'></i>
                User đăng ký mới (7 ngày)
            </div>
            <canvas id='userChart' height='100'></canvas>
        </div>
        ";
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
    options: {
    responsive: true,
    plugins: {
        legend: {
            labels: {
                font: { size: 13, weight: '600' }
            }
        },
        tooltip: {
            backgroundColor: '#0f172a',
            titleFont: { size: 13 },
            bodyFont: { size: 12 }
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: { color: 'rgba(0,0,0,0.05)' }
        },
        x: {
            grid: { display: false }
        }
    }
}
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
    options: {
    responsive: true,
    plugins: {
        legend: {
            labels: {
                font: { size: 13, weight: '600' }
            }
        },
        tooltip: {
            backgroundColor: '#0f172a',
            titleFont: { size: 13 },
            bodyFont: { size: 12 }
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: { color: 'rgba(0,0,0,0.05)' }
        },
        x: {
            grid: { display: false }
        }
    }
}
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
    options: {
    responsive: true,
    plugins: {
        legend: {
            labels: {
                font: { size: 13, weight: '600' }
            }
        },
        tooltip: {
            backgroundColor: '#0f172a',
            titleFont: { size: 13 },
            bodyFont: { size: 12 }
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: { color: 'rgba(0,0,0,0.05)' }
        },
        x: {
            grid: { display: false }
        }
    }
}
});
</script>

</body>
</html>

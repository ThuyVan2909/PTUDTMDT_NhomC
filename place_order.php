<?php
session_start();
include 'db.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Giỏ hàng trống");
}

$cart = $_SESSION['cart'];
$total = 0;

// Tính tổng
foreach ($cart as $i) {
    $sku = intval($i['sku_id']);
    $qty = intval($i['quantity']);

    $query = $conn->query("SELECT price FROM sku WHERE id = $sku");
    if (!$query || $query->num_rows == 0) continue;

    $price = $query->fetch_assoc()['price'];
    $total += $price * $qty;
}

$user_id = $_SESSION['user_id'] ?? null;
$fullname = $_POST['fullname'] ?? '';
$phone = $_POST['phone'] ?? '';
$province_id = intval($_POST['province_id'] ?? 0);
$district_id = intval($_POST['district_id'] ?? 0);
$street = $_POST['address'] ?? ''; // giữ nguyên tên input bạn đang dùng

if (!$fullname || !$phone || !$province_id || !$district_id) {
    die("Vui lòng điền đầy đủ thông tin giao hàng");
}

// Gộp địa chỉ đầy đủ
$address = $street;

// Insert đơn hàng
$stmt = $conn->prepare("
    INSERT INTO orders (user_id, total, status, fullname, phone, province_id, district_id, address)
    VALUES (?, ?, 'pending', ?, ?, ?, ?, ?)
");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param(
    "idssiiss",
    $user_id,
    $total,
    $fullname,
    $phone,
    $province_id,
    $district_id,
    $address
);

$stmt->execute();
$order_id = $stmt->insert_id;

// Lưu từng sản phẩm
foreach ($cart as $i) {
    $sku = intval($i['sku_id']);
    $qty = intval($i['quantity']);
    $price = $conn->query("SELECT price FROM sku WHERE id=$sku")->fetch_assoc()['price'];

    $conn->query("
        INSERT INTO order_items(order_id, sku_id, quantity, price)
        VALUES ($order_id, $sku, $qty, $price)
    ");

    $conn->query("
        UPDATE sku SET stock = stock - $qty WHERE id = $sku
    ");
}

// Tracking
$stmt2 = $conn->prepare("
    INSERT INTO order_tracking(order_id, status, note, updated_at)
    VALUES (?, 'pending', 'Đơn hàng đã được tạo', NOW())
");
$stmt2->bind_param("i", $order_id);
$stmt2->execute();

// Xóa giỏ
unset($_SESSION['cart']);

header("Location: order_success.php?id=$order_id");
exit;

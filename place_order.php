<?php
session_start();
require 'db.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("Giỏ hàng trống");
}

$cart = $_SESSION['cart'];
$total = 0;

// Tính tổng từ DB cho từng sản phẩm
foreach ($cart as $item) {
    $sku = intval($item['sku_id']);
    $qty = intval($item['quantity']);

    $q = $conn->prepare("SELECT price FROM sku WHERE id = ?");
    $q->bind_param("i", $sku);
    $q->execute();
    $res = $q->get_result();

    if ($res->num_rows == 0) continue;

    $price = $res->fetch_assoc()['price'];
    $total += $price * $qty;
}

// Lấy thông tin khách hàng
$user_id = $_SESSION['user_id'] ?? null;
$fullname = $_POST['fullname'] ?? '';
$phone = $_POST['phone'] ?? '';
$province_id = intval($_POST['province_id'] ?? 0);
$district_id = intval($_POST['district_id'] ?? 0);
$street = $_POST['address'] ?? '';

if (!$fullname || !$phone || !$province_id || !$district_id) {
    die("Vui lòng điền đầy đủ thông tin giao hàng");
}

$address = $street;

// -------------------------------
// XỬ LÝ COUPON
// -------------------------------
$coupon_code = $_SESSION['coupon']['code'] ?? NULL;
$coupon_discount = intval($_SESSION['coupon']['discount'] ?? 0);

// Giảm giá không được vượt quá tổng tiền
$final_price = max(0, $total - $coupon_discount);

// -------------------------------
// INSERT ĐƠN HÀNG
// -------------------------------

$stmt = $conn->prepare("
    INSERT INTO orders (
        user_id, total, discount, final_price,
        status, fullname, phone,
        province_id, district_id, street, address,
        created_at, coupon_code, coupon_discount
    )
    VALUES (?, ?, ?, ?, 'pending', ?, ?, ?, ?, ?, ?, NOW(), ?, ?)
");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param(
    "idddssiissssi",
    $user_id,
    $total,
    $coupon_discount,
    $final_price,
    $fullname,
    $phone,
    $province_id,
    $district_id,
    $street,
    $address,
    $coupon_code,
    $coupon_discount
);

$stmt->execute();
$order_id = $stmt->insert_id;

// -------------------------------
// INSERT TỪNG MẶT HÀNG TRONG ĐƠN
// -------------------------------
foreach ($cart as $item) {
    $sku = intval($item['sku_id']);
    $qty = intval($item['quantity']);

    $q2 = $conn->prepare("SELECT price FROM sku WHERE id = ?");
    $q2->bind_param("i", $sku);
    $q2->execute();
    $price = $q2->get_result()->fetch_assoc()['price'];

    $oi = $conn->prepare("
        INSERT INTO order_items (order_id, sku_id, quantity, price)
        VALUES (?, ?, ?, ?)
    ");
    $oi->bind_param("iiid", $order_id, $sku, $qty, $price);
    $oi->execute();

    // Trừ tồn kho
    $u = $conn->prepare("UPDATE sku SET stock = stock - ? WHERE id = ?");
    $u->bind_param("ii", $qty, $sku);
    $u->execute();
}

// -------------------------------
// TRACKING ĐƠN HÀNG
// -------------------------------
$track = $conn->prepare("
    INSERT INTO order_tracking (order_id, status, note, updated_at)
    VALUES (?, 'pending', 'Đơn hàng đã được tạo', NOW())
");
$track->bind_param("i", $order_id);
$track->execute();

// -------------------------------
// XÓA CART + COUPON
// -------------------------------
unset($_SESSION['cart']);
unset($_SESSION['coupon']);

header("Location: order_success.php?id=$order_id");
exit;
?>

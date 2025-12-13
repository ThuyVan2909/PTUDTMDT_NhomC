<?php
session_start();
require 'db.php';

if (!isset($_POST['coupon_code'])) {
    echo json_encode(['status' => false, 'message' => 'Không có mã giảm giá']);
    exit;
}

$code = trim($_POST['coupon_code']);
$user_id = $_SESSION['user_id'];

// Lấy mã giảm giá
$stmt = $conn->prepare("SELECT * FROM coupons WHERE code=? AND status='active'");
$stmt->bind_param("s", $code);
$stmt->execute();
$coupon = $stmt->get_result()->fetch_assoc();

if (!$coupon) {
    echo json_encode(['status' => false, 'message' => 'Mã giảm giá không hợp lệ']);
    exit;
}

$discount_value = intval($coupon['discount_value']); // Ví dụ: 200000

// Lấy giỏ hàng user
$cart = $conn->prepare("
    SELECT c.*, p.price 
    FROM cart c
    JOIN products p ON c.product_id=p.id
    WHERE c.user_id=?
");
$cart->bind_param("i", $user_id);
$cart->execute();
$items = $cart->get_result();

$total = 0;
while ($row = $items->fetch_assoc()) {
    $total += $row['price'] * $row['quantity'];
}

if ($total <= 0) {
    echo json_encode(['status' => false, 'message' => 'Giỏ hàng trống']);
    exit;
}

// Lưu coupon vào session
$_SESSION['coupon'] = [
    'code' => $code,
    'discount' => $discount_value
];

echo json_encode([
    'status' => true,
    'message' => 'Áp dụng mã giảm giá thành công',
    'discount' => $discount_value,
    'total_after_discount' => max(0, $total - $discount_value)
]);
exit;

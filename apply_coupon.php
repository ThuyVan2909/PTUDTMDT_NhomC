<?php
session_start();
require 'db.php';
header('Content-Type: application/json');

// =======================
// CHECK INPUT
// =======================
if (empty($_POST['coupon_code'])) {
    echo json_encode([
        'status' => false,
        'message' => 'Không có mã giảm giá'
    ]);
    exit;
}

$code = trim($_POST['coupon_code']);

// =======================
// LẤY THÔNG TIN COUPON
// =======================
$stmt = $conn->prepare("
    SELECT * FROM coupons 
    WHERE code = ?
      AND expired_at > NOW()
");
if (!$stmt) {
    echo json_encode([
        'status' => false,
        'message' => 'SQL ERROR: ' . $conn->error
    ]);
    exit;
}

$stmt->bind_param("s", $code);
$stmt->execute();
$coupon = $stmt->get_result()->fetch_assoc();

if (!$coupon) {
    echo json_encode([
        'status' => false,
        'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn'
    ]);
    exit;
}

$discount_value = intval($coupon['discount_amount']);
$min_total = intval($coupon['min_order_total']);

// =======================
// LẤY CART
// =======================
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo json_encode([
        'status' => false,
        'message' => 'Giỏ hàng trống'
    ]);
    exit;
}

// =======================
// TÍNH TOTAL TỪ DB
// =======================
$total = 0;

foreach ($cart as $item) {
    $sku_id = intval($item['sku_id']);
    $qty = intval($item['quantity']);

    $q = $conn->prepare("SELECT price FROM sku WHERE id = ?");
    if (!$q) {
        echo json_encode([
            'status' => false,
            'message' => 'SQL ERROR (SKU): ' . $conn->error
        ]);
        exit;
    }

    $q->bind_param("i", $sku_id);
    $q->execute();
    $priceRow = $q->get_result()->fetch_assoc();

    if (!$priceRow) continue;

    $total += intval($priceRow['price']) * $qty;
}

// Giỏ hàng rỗng sau khi validate SKU
if ($total <= 0) {
    echo json_encode([
        'status' => false,
        'message' => 'Giỏ hàng không hợp lệ hoặc sản phẩm không tồn tại'
    ]);
    exit;
}

// =======================
// CHECK MINIMUM ORDER
// =======================
if ($total < $min_total) {
    echo json_encode([
        'status' => false,
        'message' => 'Đơn hàng chưa đạt mức tối thiểu ' . number_format($min_total) . 'đ'
    ]);
    exit;
}

// =======================
// GIỚI HẠN DISCOUNT <= TOTAL
// =======================
if ($discount_value > $total) {
    $discount_value = $total;
}

// =======================
// LƯU SESSION
// =======================
$_SESSION['coupon_code'] = $code;
$_SESSION['coupon_discount'] = $discount_value;

// =======================
// RETURN JSON
// =======================
echo json_encode([
    'status' => true,
    'message' => 'Áp dụng mã giảm giá thành công',
    'discount' => $discount_value,
    'total_after_discount' => $total - $discount_value
]);
exit;

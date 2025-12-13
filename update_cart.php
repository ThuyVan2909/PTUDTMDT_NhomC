<?php
session_start();
require 'db.php';

header("Content-Type: application/json");

$sku_id = intval($_POST['sku_id'] ?? 0);
$quantity = intval($_POST['quantity'] ?? 1);

if ($sku_id <= 0) {
    echo json_encode(["status" => false, "message" => "SKU không hợp lệ"]);
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];

// ==========================
// Cập nhật số lượng hoặc xóa sản phẩm
// ==========================
foreach ($cart as $k => $item) {
    if ($item['sku_id'] == $sku_id) {
        if ($quantity <= 0) {
            unset($cart[$k]);
        } else {
            $cart[$k]['quantity'] = $quantity;
        }
        break;
    }
}

$cart = array_values($cart);
$_SESSION['cart'] = $cart;

// ==========================
// Tính subtotal
// ==========================
$subtotal = 0;

foreach ($cart as $item) {
    $id = intval($item['sku_id']);
    $stmt = $conn->prepare("SELECT price FROM sku WHERE id = ?");
    if (!$stmt) continue;  // bảo vệ SQL
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $price = intval($result['price'] ?? 0);
    $qty = intval($item['quantity']);

    $subtotal += $price * $qty;
}

// ==========================
// Kiểm tra coupon đã áp dụng
// ==========================
$discount = $_SESSION['coupon_discount'] ?? 0;

// Nếu giỏ hàng trống → xoá coupon
if (empty($cart)) {
    unset($_SESSION['coupon_code']);
    unset($_SESSION['coupon_discount']);
    $discount = 0;
}

// Final total
$final_total = max(0, $subtotal - $discount);

echo json_encode([
    "status" => true,
    "message" => "Cập nhật giỏ hàng thành công",
    "subtotal" => $subtotal,
    "discount" => $discount,
    "final_total" => $final_total
]);
exit;
?>

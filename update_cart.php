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
$item_subtotal = 0;

foreach ($cart as $k => $item) {
    if ($item['sku_id'] == $sku_id) {
        if ($quantity <= 0) {
            unset($cart[$k]);
        } else {
            $cart[$k]['quantity'] = $quantity;

            $price = ($item['promo_price'] > 0)
                ? intval($item['promo_price'])
                : intval($item['price']);

            $item_subtotal = $price * $quantity;
        }
        break;
    }
}


$cart = array_values($cart);
$_SESSION['cart'] = $cart;

// ==========================
// Tính subtotal
// ==========================
// ==========================
// Tính subtotal (DÙNG GIÁ TRONG CART)
// ==========================
$subtotal = 0;

foreach ($cart as $item) {
    $price = isset($item['promo_price']) && $item['promo_price'] > 0
        ? intval($item['promo_price'])
        : intval($item['price']);

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
    "item_subtotal" => $item_subtotal,
    "discount" => $discount,
    "final_total" => $final_total
]);
exit;

?>

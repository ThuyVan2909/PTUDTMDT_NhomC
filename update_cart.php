<?php
session_start();

$sku_id = intval($_POST['sku_id'] ?? 0);
$quantity = intval($_POST['quantity'] ?? 1);

if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$cart = $_SESSION['cart'];

// Xóa hoặc cập nhật quantity
foreach($cart as $k => $item){
    if($item['sku_id'] == $sku_id){
        if($quantity <= 0){
            unset($cart[$k]);
        } else {
            $cart[$k]['quantity'] = $quantity;
        }
        break;
    }
}

$_SESSION['cart'] = array_values($cart);
echo "Cập nhật giỏ hàng thành công";

<?php
session_start();
include 'db.php';

$sku_id = intval($_POST['sku_id'] ?? 0);
$qty = intval($_POST['quantity'] ?? 1);

if (!$sku_id || $qty < 1) {
    echo "Sản phẩm không hợp lệ";
    exit;
}

// Lấy SKU + SPU
$sku = $conn->query("SELECT s.id, s.price, s.promo_price, s.spu_id, spu.name AS spu_name
                     FROM sku s
                     JOIN spu ON s.spu_id = spu.id
                     WHERE s.id=$sku_id")->fetch_assoc();
if (!$sku) { echo "Sản phẩm không tồn tại"; exit; }

// Lấy thuộc tính
$attrs_res = $conn->query("
    SELECT a.name AS attr_name, av.value AS attr_value
    FROM sku_attribute_values sav
    JOIN attribute_values av ON sav.attribute_value_id = av.id
    JOIN attributes a ON av.attribute_id = a.id
    WHERE sav.sku_id = $sku_id
");
$attrs = [];
while($r = $attrs_res->fetch_assoc()) {
    $attrs[$r['attr_name']] = $r['attr_value'];
}

// Chuẩn bị item cart
$item = [
    'sku_id' => $sku['id'],
    'spu_name' => $sku['spu_name'],
    'quantity' => $qty,
    'price' => $sku['price'],
    'promo_price' => $sku['promo_price'],
    'attributes' => $attrs
];

// Khởi tạo cart
if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Nếu SKU đã có trong cart → cộng quantity
$found = false;
foreach($_SESSION['cart'] as &$c){
    if($c['sku_id'] == $sku_id && $c['attributes'] == $attrs){
        $c['quantity'] += $qty;
        $found = true;
        break;
    }
}
if(!$found) $_SESSION['cart'][] = $item;

echo "Đã thêm vào giỏ hàng";

<?php
session_start();

$sku_id = intval($_POST['sku_id'] ?? 0);

if (!isset($_SESSION['cart'])) {
    echo json_encode(["status"=>false, "message"=>"Cart empty"]);
    exit;
}

foreach($_SESSION['cart'] as $k => $item){
    if($item['sku_id'] == $sku_id){
        unset($_SESSION['cart'][$k]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
        echo json_encode(["status"=>true, "message"=>"Removed"]);
        exit;
    }
}

echo json_encode(["status"=>false, "message"=>"Item not found"]);
exit;

<?php
include "../db.php";

if($_SERVER['REQUEST_METHOD']==='POST'){
    $code = $_POST['code'];
    $discount = (float)$_POST['discount_amount'];
    $minOrder = (float)$_POST['min_order_total'];
    $expired = $_POST['expired_at'];

    $stmt = $conn->prepare("
        INSERT INTO coupons (code, discount_amount, min_order_total, expired_at)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("sdds", $code, $discount, $minOrder, $expired);

    if($stmt->execute()){
        echo json_encode(['success'=>true]);
    } else {
        echo json_encode(['success'=>false,'error'=>$conn->error]);
    }
}
?>

<?php
include "../db.php";
header('Content-Type: application/json');

$id = intval($_POST['id']);

$stmt = $conn->prepare("
    UPDATE coupons
    SET code=?, discount_amount=?, min_order_total=?, expired_at=?
    WHERE id=?
");

$stmt->bind_param(
    "siisi",
    $_POST['code'],
    $_POST['discount_amount'],
    $_POST['min_order_total'],
    $_POST['expired_at'],
    $id
);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'data' => [
            'code' => $_POST['code'],
            'discount_amount' => $_POST['discount_amount'],
            'min_order_total' => $_POST['min_order_total'],
            'expired_at' => $_POST['expired_at']
        ]
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Không cập nhật được'
    ]);
}

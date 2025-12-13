<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Not login"]);
    exit;
}

$order_item_id = intval($_POST['order_item_id']);
$reason        = trim($_POST['reason']);
$user_id       = $_SESSION['user_id'];

/* Kiểm tra món có thuộc user không */
$sql = "SELECT oi.*, o.user_id 
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.id
        WHERE oi.id=? AND o.user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_item_id, $user_id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

if (!$item) {
    echo json_encode(["error" => "Permission denied"]);
    exit;
}

/* Kiểm tra đã yêu cầu trả hàng chưa */
$sql2 = "SELECT id FROM order_item_returns WHERE order_item_id=?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $order_item_id);
$stmt2->execute();
$exists = $stmt2->get_result()->fetch_assoc();

if ($exists) {
    echo json_encode(["error" => "Đã yêu cầu trả món này rồi"]);
    exit;
}

/* Lưu yêu cầu trả hàng */
$sql3 = "INSERT INTO order_item_returns 
            (order_id, order_item_id, sku_id, quantity, reason, status)
         VALUES (?,?,?,?,?, 'pending')";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param(
    "iiiss",
    $item['order_id'],
    $order_item_id,
    $item['sku_id'],
    $item['quantity'],
    $reason
);
$stmt3->execute();

echo json_encode(["success" => true]);

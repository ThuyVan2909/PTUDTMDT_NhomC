<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Not login"]);
    exit;
}

$order_id = intval($_GET['id']);
$user_id  = $_SESSION['user_id'];

/* Lấy thông tin đơn */
$sql = "SELECT * FROM orders WHERE id=? AND user_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo json_encode(["error" => "Order not found"]);
    exit;
}

/* Lấy danh sách item */
$sql2 = "SELECT 
            oi.id AS order_item_id,
            oi.sku_id,
            oi.quantity,
            oi.price,
            s.sku_code,
            s.variant
        FROM order_items oi
        JOIN sku s ON oi.sku_id = s.id
        WHERE oi.order_id=?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $order_id);
$stmt2->execute();
$items = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    "order" => $order,
    "items" => $items
]);

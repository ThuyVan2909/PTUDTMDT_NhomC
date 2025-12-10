<?php
include 'db.php';

$order_id = $_POST['order_id'];
$status = $_POST['status'];
$note = $_POST['note'];

$conn->query("UPDATE orders SET status = '$status' WHERE id = $order_id");

$conn->query("
INSERT INTO order_tracking(order_id, status, note)
VALUES ($order_id, '$status', '$note')
");

echo json_encode(['success' => true]);

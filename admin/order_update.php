<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin.php?view=orders");
    exit;
}

if (!isset($_POST['order_id'], $_POST['status'])) {
    header("Location: admin.php?view=orders&error=missing");
    exit;
}

$order_id = intval($_POST['order_id']);
$status   = $_POST['status'];

$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $order_id);
$stmt->execute();

header("Location: admin.php?view=orders&updated=1");
exit;

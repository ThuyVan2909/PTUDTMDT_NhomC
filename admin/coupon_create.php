<?php
session_start();
header('Content-Type: application/json');
include "../db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$code     = trim($_POST['code'] ?? '');
$discount = (float)($_POST['discount_amount'] ?? 0);
$minOrder = (float)($_POST['min_order_total'] ?? 0);
$expired  = $_POST['expired_at'] ?? '';

if ($code === '' || $expired === '') {
    echo json_encode(['success' => false, 'error' => 'Thiếu dữ liệu']);
    exit;
}

/* CHUẨN HÓA DATETIME */
$expired = str_replace('T', ' ', $expired);
if (strlen($expired) === 16) {
    $expired .= ':00';
}

/* CHECK TRÙNG CODE */
$check = $conn->prepare("SELECT id FROM coupons WHERE code = ?");
$check->bind_param("s", $code);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(['success' => false, 'error' => 'Mã coupon đã tồn tại']);
    exit;
}

/* INSERT */
$stmt = $conn->prepare("
    INSERT INTO coupons (code, discount_amount, min_order_total, expired_at)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("sdds", $code, $discount, $minOrder, $expired);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

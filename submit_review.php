<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    exit("NOT_LOGIN");
}

$user_id = (int)$_SESSION['user_id'];
$spu_id  = (int)$_POST['spu_id'];
$rating  = (int)$_POST['rating'];
$comment = trim($_POST['comment']);

// CHECK ĐÃ MUA
$stmt = $conn->prepare("
    SELECT 1
    FROM orders o
    JOIN order_items oi ON oi.order_id = o.id
    JOIN sku s ON s.id = oi.sku_id
    WHERE o.user_id = ? AND s.spu_id = ?
    LIMIT 1
");
$stmt->bind_param("ii", $user_id, $spu_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    exit("NOT_PURCHASED");
}

// INSERT REVIEW
$stmt = $conn->prepare("
    INSERT INTO product_reviews (user_id, spu_id, rating, comment)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("iiis", $user_id, $spu_id, $rating, $comment);
$stmt->execute();

echo "OK";


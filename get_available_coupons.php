<?php
session_start();
include 'db.php';
header('Content-Type: application/json');

$cart = $_SESSION['cart'] ?? [];
$total = 0;

foreach ($cart as $item) {
    $price = $item['promo_price'] ?? $item['price'];
    $total += $price * $item['quantity'];
}

$now = date('Y-m-d H:i:s');

$sql = "SELECT * FROM coupons WHERE expired_at > ? ORDER BY discount_amount DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $now);
$stmt->execute();
$res = $stmt->get_result();

$data = [];

while ($c = $res->fetch_assoc()) {
    $eligible = $total >= $c['min_order_total'];

    $data[] = [
    'id' => $c['id'],
    'code' => $c['code'],
    'discount' => (int)$c['discount_amount'],
    'min_order' => (int)$c['min_order_total'],
    'expired_at' => date('d/m/Y', strtotime($c['expired_at'])),
    'eligible' => $eligible,
    'reason' => $eligible ? '' : 'Đơn hàng tối thiểu ' . number_format($c['min_order_total']) . ' đ'
];

}

echo json_encode([
    'total' => $total,
    'coupons' => $data
]);

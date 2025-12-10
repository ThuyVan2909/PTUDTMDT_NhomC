<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
$session_id = session_id();

$where = $user_id
    ? "c.user_id = " . intval($user_id)
    : "c.session_id = '" . $conn->real_escape_string($session_id) . "'";


$sql = "
SELECT 
    c.id AS cart_id,
    c.sku_id,
    c.quantity,
    p.spu_name,
    s.price,
    (s.price * c.quantity) AS total_price,
    img.image_url
FROM cart c
JOIN sku s ON s.id = c.sku_id
JOIN spu p ON p.id = s.spu_id
LEFT JOIN sku_images img ON img.sku_id = s.id AND img.is_primary = 1
WHERE $where
";

$res = $conn->query($sql);
$data = [];

while ($row = $res->fetch_assoc()) {
    $row['image_url'] = "/techzone/" . ltrim($row['image_url'], '/');
    $data[] = $row;
}

echo json_encode($data);

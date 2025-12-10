<?php
include 'db.php';

$spu_id = intval($_GET['spu_id'] ?? 0);
$capacity_value_id = intval($_GET['capacity_value_id'] ?? 0);

if (!$spu_id || !$capacity_value_id) {
    echo json_encode([]);
    exit;
}

// tìm sku có dung lượng tương ứng
$sql = "
SELECT s.id, s.price, s.promo_price
FROM sku s
JOIN sku_attribute_values sav ON sav.sku_id = s.id
WHERE s.spu_id = $spu_id AND sav.attribute_value_id = $capacity_value_id
LIMIT 1
";
$sku = $conn->query($sql)->fetch_assoc();

if (!$sku) {
    echo json_encode([]);
    exit;
}

header('Content-Type: application/json');
echo json_encode($sku);

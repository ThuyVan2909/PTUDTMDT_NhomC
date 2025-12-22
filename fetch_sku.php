<?php
include 'db.php';

$input = file_get_contents("php://input");
$data = json_decode($input, true);

$spu_id = intval($data['spu_id'] ?? 0);
$values = $data['values'] ?? [];

if (!$spu_id || !is_array($values) || count($values) == 0) {
    echo json_encode(['error'=>'Thiếu dữ liệu']);
    exit;
}

$vals = array_map('intval', $values);
$vals_list = implode(',', $vals);

$sql = "
SELECT s.id AS sku_id, s.price, s.promo_price, s.stock
FROM sku s
JOIN sku_attribute_values sav ON sav.sku_id = s.id
WHERE s.spu_id = $spu_id
  AND sav.attribute_value_id IN ($vals_list)
GROUP BY s.id, s.price, s.promo_price, s.stock
HAVING COUNT(DISTINCT sav.attribute_value_id) = ".count($vals)."
LIMIT 1
";

$res = $conn->query($sql);

header('Content-Type: application/json');

if ($res && $res->num_rows > 0) {
    echo json_encode($res->fetch_assoc());
} else {
    echo json_encode([]);
}

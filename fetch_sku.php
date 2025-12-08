<?php
include 'db.php';

$spu_id = intval($_POST['spu_id'] ?? 0);
$values = $_POST['values'] ?? [];

if (!$spu_id || !is_array($values)) {
    echo json_encode(['error' => 'Thiếu dữ liệu']);
    exit;
}

$vals = array_map('intval', $values);
$vals_list = implode(",", $vals);

$sql = "
SELECT 
    s.id AS sku_id, 
    s.price, 
    s.promo_price,
    (
        SELECT image_url 
        FROM sku_images 
        WHERE sku_id = s.id 
        ORDER BY is_primary DESC, id ASC 
        LIMIT 1
    ) AS image
FROM sku s
JOIN sku_attribute_values sav ON sav.sku_id = s.id
WHERE s.spu_id = $spu_id
  AND sav.attribute_value_id IN ($vals_list)
GROUP BY s.id, s.price, s.promo_price
HAVING COUNT(DISTINCT sav.attribute_value_id) = ".count($vals)."
LIMIT 1
";

$res = $conn->query($sql);
if ($res && $res->num_rows) {
    echo json_encode($res->fetch_assoc());
} else {
    echo json_encode([]);
}

<?php
include 'db.php';

$spu_id = intval($_GET['spu_id']);
$values = json_decode($_GET['values'], true);

if (!$spu_id || !$values) {
    echo json_encode(['error' => 'Missing data']);
    exit;
}

$conditions = [];
foreach ($values as $attrId => $valId) {
    $conditions[] = "
        sku.id IN (
            SELECT sku_id FROM sku_attribute_values WHERE attribute_value_id = $valId
        )
    ";
}

$sql = "SELECT id FROM sku WHERE spu_id = $spu_id AND " . implode(" AND ", $conditions) . " LIMIT 1";

$res = $conn->query($sql);
$row = $res->fetch_assoc();

echo json_encode([
    "sku_id" => $row['id'] ?? null
]);

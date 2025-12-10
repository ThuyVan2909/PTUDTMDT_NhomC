<?php
include 'db.php';

$spu_id = intval($_GET['spu_id'] ?? 0);
$color_value_id = intval($_GET['color_value_id'] ?? 0);

if (!$spu_id || !$color_value_id) {
    echo json_encode([]);
    exit;
}

$sql = "
SELECT s.id 
FROM sku s
JOIN sku_attribute_values sav ON sav.sku_id = s.id
WHERE s.spu_id = $spu_id AND sav.attribute_value_id = $color_value_id
LIMIT 1
";
$sku = $conn->query($sql)->fetch_assoc();

if (!$sku) {
    echo json_encode([]);
    exit;
}

$sku_id = $sku['id'];

$res = $conn->query("SELECT image_url FROM sku_images WHERE sku_id = $sku_id ORDER BY is_primary DESC");

$imgs = [];
while ($r = $res->fetch_assoc()) {
    $img = $r['image_url'];

    // Fix path — tránh lặp lại /techzone
    if (str_starts_with($img, "/techzone/")) {
        $imgs[] = $img;
    } else {
        $imgs[] = "/techzone" . $img;
    }
}

header('Content-Type: application/json');
echo json_encode($imgs);

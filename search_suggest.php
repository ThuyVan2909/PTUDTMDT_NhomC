<?php
include 'db.php';

$q = trim($_GET['q'] ?? '');

if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("
    SELECT 
    s.id,
    s.name,
    MIN(sk.price) AS price,
    (
        SELECT si.image_url 
        FROM sku_images si
        WHERE si.sku_id = sk.id
        LIMIT 1
    ) AS image
FROM spu s
JOIN sku sk ON sk.spu_id = s.id
WHERE s.name LIKE ?
GROUP BY s.id, s.name
ORDER BY s.created_at DESC
LIMIT 10

");

$keyword = "%" . $q . "%";
$stmt->bind_param("s", $keyword);
$stmt->execute();

$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header("Content-Type: application/json");
echo json_encode($data);

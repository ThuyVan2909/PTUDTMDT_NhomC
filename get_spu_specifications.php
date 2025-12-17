<?php
include 'db.php';

$spu_id = intval($_GET['spu_id'] ?? 0);

if (!$spu_id) {
    echo json_encode([]);
    exit;
}

$sql = "
SELECT spec_name, spec_value
FROM spu_specifications
WHERE spu_id = $spu_id
ORDER BY sort_order
";

$res = $conn->query($sql);

if (!$res) {
    http_response_code(500);
    echo json_encode([
        'error' => $conn->error
    ]);
    exit;
}

$data = [];
while ($r = $res->fetch_assoc()) {
    $data[] = $r;
}

header('Content-Type: application/json');
echo json_encode($data);

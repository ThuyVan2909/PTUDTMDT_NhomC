<?php
include 'db.php';

$order_id = intval($_GET['order_id']);

$sql = "
SELECT status, note, created_at
FROM order_tracking
WHERE order_id = $order_id
ORDER BY id ASC
";

$res = $conn->query($sql);
$data = [];

while ($r = $res->fetch_assoc()) $data[] = $r;

echo json_encode($data);

<?php
include 'db.php';
$data = [];
$result = $conn->query("SELECT id,name FROM provinces ORDER BY name ASC");
while($row = $result->fetch_assoc()){
    $data[] = $row;
}
header('Content-Type: application/json');
echo json_encode($data);
?>

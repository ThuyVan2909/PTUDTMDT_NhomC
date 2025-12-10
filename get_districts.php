<?php
include 'db.php';

$province_id = $_GET['province_id'] ?? null;

if (!$province_id) {
    echo '<option value="">--Chọn quận/huyện--</option>';
    exit;
}

// Lấy quận/huyện theo province_id
$stmt = $conn->prepare("SELECT id, name FROM districts WHERE province_id = ? ORDER BY name ASC");
$stmt->bind_param("i", $province_id);
$stmt->execute();
$result = $stmt->get_result();

echo '<option value="">--Chọn quận/huyện--</option>';
while ($row = $result->fetch_assoc()) {
    echo '<option value="'.$row['id'].'">'.htmlspecialchars($row['name']).'</option>';
}

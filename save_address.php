<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => false, "msg" => "Not logged in"]);
    exit;
}

$uid = $_SESSION['user_id'];

$province = $_POST['province'] ?? null;
$district = $_POST['district'] ?? null;
$street   = $_POST['street'] ?? null;

if (!$province || !$district || !$street) {
    echo json_encode(["status" => false, "msg" => "Thiếu dữ liệu"]);
    exit;
}

$stmt = $conn->prepare("
    UPDATE users SET province_id=?, district_id=?, street=? WHERE id=?
");
$stmt->bind_param("iisi", $province, $district, $street, $uid);
$stmt->execute();

echo json_encode(["status" => true, "msg" => "Đã lưu thành công"]);

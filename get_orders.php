<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT id, total, status, created_at 
        FROM orders 
        WHERE user_id = $user_id 
        ORDER BY id DESC";

$res = $conn->query($sql);
$data = [];

while ($r = $res->fetch_assoc()) $data[] = $r;

echo json_encode($data);

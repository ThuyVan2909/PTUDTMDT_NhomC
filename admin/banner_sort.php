<?php
include "../db.php";
$data = json_decode(file_get_contents("php://input"), true);

foreach ($data as $item) {
    $id = (int)$item['id'];
    $order = (int)$item['order'];
    $conn->query("UPDATE banners SET sort_order = $order WHERE id = $id");
}

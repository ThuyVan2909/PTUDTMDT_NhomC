<?php
include "../db.php";

$id = intval($_GET['id'] ?? 0);

if($id > 0){
    // Kiểm tra tồn tại trước khi xóa
    $banner = $conn->query("SELECT id FROM banners WHERE id = $id")->fetch_assoc();
    if($banner){
        $conn->query("DELETE FROM banners WHERE id = $id");
    }
}

// Quay lại trang banner
header("Location: admin.php?view=banners");
exit;
?>

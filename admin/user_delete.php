<?php
include "../db.php";

$id = intval($_POST['id']);

// Không cho tự xóa
if ($id == $_SESSION['user_id']) {
    header("Location: users.php");
    exit;
}

$conn->query("DELETE FROM users WHERE id = $id");
header("Location: users.php");

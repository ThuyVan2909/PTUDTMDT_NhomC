<?php
include "../db.php";

$id       = intval($_POST['id']);
$fullname = trim($_POST['fullname']);
$email    = trim($_POST['email']);
$role     = $_POST['role'];

// Không cho tự hạ quyền
if ($id == $_SESSION['user_id'] && $role !== 'admin') {
    header("Location: users.php?error=self_role");
    exit;
}

$stmt = $conn->prepare("
    UPDATE users 
    SET fullname=?, email=?, role=? 
    WHERE id=?
");
$stmt->bind_param("sssi", $fullname, $email, $role, $id);
$stmt->execute();

header("Location: users.php");

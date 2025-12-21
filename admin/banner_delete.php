<?php
include "../db.php";

$id = intval($_GET['id']);

$conn->query("DELETE FROM banners WHERE id = $id");

header("Location: banners.php");
exit;

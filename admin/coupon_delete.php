<?php
include "../db.php";

$id = intval($_GET['id']);
$conn->query("DELETE FROM coupons WHERE id=$id");

header("Location: admin.php?view=coupons");
exit;

<?php
session_start();

/* Xoá toàn bộ session */
$_SESSION = [];

/* Huỷ session */
session_destroy();

/* Quay về trang chủ */
header("Location: ../index.php");
exit;

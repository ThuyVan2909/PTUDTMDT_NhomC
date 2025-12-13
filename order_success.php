<?php
$id = intval($_GET['id'] ?? 0);
?>
<h2>Đặt hàng thành công!</h2>
<p>Mã đơn hàng: <?= $id ?></p>
<a href="index.php">Quay về trang chủ</a>

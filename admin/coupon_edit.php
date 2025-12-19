<?php
include "../db.php";

$id = intval($_GET['id']);
$coupon = $conn->query("SELECT * FROM coupons WHERE id=$id")->fetch_assoc();

if (!$coupon) {
    die("Coupon không tồn tại");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("
        UPDATE coupons
        SET code=?, discount_amount=?, min_order_total=?, expired_at=?
        WHERE id=?
    ");
    $stmt->bind_param(
        "siisi",
        $_POST['code'],
        $_POST['discount_amount'],
        $_POST['min_order_total'],
        $_POST['expired_at'],
        $id
    );
    $stmt->execute();

    header("Location: admin.php?view=coupons");
    exit;
}
?>

<h3>Sửa Coupon</h3>
<form method="post">
    Code:<br>
    <input name="code" value="<?= $coupon['code'] ?>"><br>

    Giảm giá (₫):<br>
    <input name="discount_amount" value="<?= $coupon['discount_amount'] ?>"><br>

    Đơn tối thiểu (₫):<br>
    <input name="min_order_total" value="<?= $coupon['min_order_total'] ?>"><br>

    Hết hạn:<br>
    <input type="datetime-local" name="expired_at"
           value="<?= date('Y-m-d\TH:i', strtotime($coupon['expired_at'])) ?>"><br><br>

    <button>Cập nhật</button>
</form>

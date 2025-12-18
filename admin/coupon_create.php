<?php
include "../db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("
        INSERT INTO coupons (code, discount_amount, min_order_total, expired_at)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "siis",
        $_POST['code'],
        $_POST['discount_amount'],
        $_POST['min_order_total'],
        $_POST['expired_at']
    );
    $stmt->execute();

    header("Location: admin.php?view=coupons");
    exit;
}
?>

<h3>Thêm Coupon</h3>
<form method="post">
    Code:<br>
    <input name="code" required><br>

    Giảm giá (₫):<br>
    <input type="number" name="discount_amount" required><br>

    Đơn tối thiểu (₫):<br>
    <input type="number" name="min_order_total" required><br>

    Hết hạn:<br>
    <input type="datetime-local" name="expired_at" required><br><br>

    <button>Lưu</button>
</form>

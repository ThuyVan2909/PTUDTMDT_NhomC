<?php
include "../db.php";

$coupons = $conn->query("SELECT * FROM coupons ORDER BY id DESC");
?>

<div class="card">
    <h3>Quản lý Coupon</h3>
    <a href="coupon_create.php">+ Thêm coupon</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Giảm (₫)</th>
            <th>Đơn tối thiểu (₫)</th>
            <th>Hết hạn</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>

        <?php while ($c = $coupons->fetch_assoc()): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= $c['code'] ?></td>
            <td><?= number_format($c['discount_amount']) ?></td>
            <td><?= number_format($c['min_order_total']) ?></td>
            <td><?= $c['expired_at'] ?></td>
            <td><?= $c['created_at'] ?></td>
            <td>
                <a href="coupon_edit.php?id=<?= $c['id'] ?>">Sửa</a> |
                <a href="coupon_delete.php?id=<?= $c['id'] ?>"
                   onclick="return confirm('Xóa coupon này?')">Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

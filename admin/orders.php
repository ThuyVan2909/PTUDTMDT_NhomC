<?php
include '../db.php';



/*
|--------------------------------------------------------------------------
| ORDER STATUS ENUM (PHẢI KHỚP DB)
|--------------------------------------------------------------------------
*/
$statuses = [
    'Đã đặt',
    'Người bán đang chuẩn bị hàng',
    'Đơn vị giao hàng đã nhận hàng',
    'Hàng đang giao đến nhà bạn',
    'Đơn hàng đã giao',
    'Đơn bị huỷ'
];

/*
|--------------------------------------------------------------------------
| GET ORDERS
|--------------------------------------------------------------------------
*/
$sql = "
SELECT 
    o.id,
    o.user_id,
    o.fullname,
    o.phone,
    o.total,
    o.discount,
    o.final_price,
    o.status,
    o.payment_method,
    o.shipping_method,
    o.address,
    o.created_at
FROM orders o
ORDER BY o.created_at DESC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin-order.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <h2 class="mb-4">Quản lý đơn hàng</h2>

    <table class="table order-table align-middle">
        <thead class="table-dark text-center">
        <tr>
            <th>ID</th>
            <th>Khách hàng</th>
            <th>Điện thoại</th>
            <th>Tổng tiền</th>
            <th>Giảm giá</th>
            <th>Thanh toán</th>
            <th>Trạng thái</th>
            <th>Ngày đặt</th>
            <th>Hành động</th>
        </tr>
        </thead>

        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="text-center">#<?= $row['id'] ?></td>

                    <td>
                        <?= htmlspecialchars($row['fullname']) ?><br>
                        <small class="text-muted">User ID: <?= $row['user_id'] ?></small>
                    </td>

                    <td><?= htmlspecialchars($row['phone']) ?></td>

                    <td><?= number_format($row['total'], 0, ',', '.') ?> ₫</td>

                    <td><?= number_format($row['discount'], 0, ',', '.') ?> ₫</td>

                    <td>
                        <?= htmlspecialchars($row['payment_method'] ?? '-') ?><br>
                        <small><?= htmlspecialchars($row['shipping_method'] ?? '-') ?></small>
                    </td>

                    <td>
                        <?php
$selectClass = '';
switch ($row['status']) {
    case 'Đã đặt': $selectClass = 'st-new'; break;
    case 'Người bán đang chuẩn bị hàng': $selectClass = 'st-preparing'; break;
    case 'Đơn vị giao hàng đã nhận hàng': $selectClass = 'st-picked'; break;
    case 'Hàng đang giao đến nhà bạn': $selectClass = 'st-shipping'; break;
    case 'Đơn hàng đã giao': $selectClass = 'st-done'; break;
    case 'Đơn bị huỷ': $selectClass = 'st-cancel'; break;
}
?>
    <form method="POST" action="order_update.php" class="d-flex gap-2 align-items-center">
        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">

        <select name="status" class="form-select form-select-sm status-select <?= $selectClass ?>">
            <?php foreach ($statuses as $st): ?>
                <option value="<?= $st ?>" <?= $st === $row['status'] ? 'selected' : '' ?>>
                    <?= $st ?>
                </option>
            <?php endforeach; ?>
        </select>
</td>

<td><?= $row['created_at'] ?></td>

<td class="text-center">
        <button type="submit" class="btn btn-sm btn-primary">
            Cập nhật
        </button>
    </form>
</td>

                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" class="text-center text-muted">Chưa có đơn hàng</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<script>
document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function () {

        // remove toàn bộ class màu cũ
        this.classList.remove(
            'st-new',
            'st-preparing',
            'st-picked',
            'st-shipping',
            'st-done',
            'st-cancel'
        );

        // map giá trị -> class
        const map = {
            'Đã đặt': 'st-new',
            'Người bán đang chuẩn bị hàng': 'st-preparing',
            'Đơn vị giao hàng đã nhận hàng': 'st-picked',
            'Hàng đang giao đến nhà bạn': 'st-shipping',
            'Đơn hàng đã giao': 'st-done',
            'Đơn bị huỷ': 'st-cancel'
        };

        // add class mới
        this.classList.add(map[this.value]);
    });
});
</script>

</body>
</html>

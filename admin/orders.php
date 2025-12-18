<?php
include '../db.php';

/*
|--------------------------------------------------------------------------
| UPDATE ORDER STATUS
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = intval($_POST['order_id']);
    $status   = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();

    header("Location: orders.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| ORDER STATUS ENUM (PHẢI KHỚP DB)
|--------------------------------------------------------------------------
*/
$statuses = [
    'Đã đặt',
    'Người bán đang chuẩn bị hàng',
    'Đang giao hàng',
    'Đã giao',
    'Đã hủy'
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
</head>
<body class="bg-light">

<div class="container py-4">
    <h3 class="mb-4">Quản lý đơn hàng</h3>

    <table class="table table-bordered table-hover align-middle">
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
                        <form method="POST" class="d-flex gap-2">
                            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">

                            <select name="status" class="form-select form-select-sm">
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

</body>
</html>

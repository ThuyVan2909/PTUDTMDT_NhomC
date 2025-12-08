<?php
include "../db.php";  // đúng đường dẫn

// Xử lý cập nhật trạng thái
if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
}

// Lấy danh sách đơn hàng
$orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Quản lý đơn hàng</title>
<style>
body { font-family: Arial; background: #f5f5f5; }
table { width: 100%; background: white; border-collapse: collapse; margin-top: 20px; }
th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
th { background: #135071; color: white; }
select, button { padding: 6px 8px; }
</style>
</head>
<body>

<h2>Quản lý đơn hàng</h2>

<table>
<tr>
    <th>ID</th>
    <th>Người đặt</th>
    <th>SĐT</th>
    <th>Địa chỉ</th>
    <th>Tổng tiền</th>
    <th>Trạng thái</th>
    <th>Ngày tạo</th>
    <th>Hành động</th>
</tr>

<?php while($o = $orders->fetch_assoc()): ?>
<tr>
    <td><?= $o['id'] ?></td>
    <td><?= htmlspecialchars($o['fullname']) ?></td>
    <td><?= htmlspecialchars($o['phone']) ?></td>
    <td><?= htmlspecialchars($o['address']) ?></td>
    <td><?= number_format($o['total']) ?>₫</td>
    <td><?= $o['status'] ?></td>
    <td><?= $o['created_at'] ?></td>

    <td>
        <form method="POST">
            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">

            <select name="status">
                <option value="Pending"   <?= $o['status']=="Pending" ? "selected" : "" ?>>Pending</option>
                <option value="Processing" <?= $o['status']=="Processing" ? "selected" : "" ?>>Processing</option>
                <option value="Shipping"   <?= $o['status']=="Shipping" ? "selected" : "" ?>>Shipping</option>
                <option value="Delivered"  <?= $o['status']=="Delivered" ? "selected" : "" ?>>Delivered</option>
            </select>

            <button type="submit" name="update_status">Cập nhật</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>

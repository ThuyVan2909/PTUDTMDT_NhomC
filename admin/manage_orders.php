<?php
session_start();
include '../db.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit;
}

if(isset($_POST['update_status'])){
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si",$status,$order_id);
    $stmt->execute();
}

$sql = "SELECT * FROM orders";
$orders = $conn->query($sql);
?>

<h2>Quản lý đơn hàng</h2>
<table>
<tr><th>ID</th><th>Người đặt</th><th>Địa chỉ</th><th>Thanh toán</th><th>Trạng thái</th><th>Hành động</th></tr>
<?php while($row = $orders->fetch_assoc()): ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['user_id']; ?></td>
    <td><?php echo $row['address']; ?></td>
    <td><?php echo $row['payment']; ?></td>
    <td><?php echo $row['status']; ?></td>
    <td>
        <form method="POST">
            <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>" />
            <select name="status">
                <option value="Pending" <?php if($row['status']=='Pending') echo 'selected'; ?>>Pending</option>
                <option value="Processing" <?php if($row['status']=='Processing') echo 'selected'; ?>>Processing</option>
                <option value="Shipping" <?php if($row['status']=='Shipping') echo 'selected'; ?>>Shipping</option>
                <option value="Delivered" <?php if($row['status']=='Delivered') echo 'selected'; ?>>Delivered</option>
            </select>
            <button type="submit" name="update_status">Cập nhật</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
</table>

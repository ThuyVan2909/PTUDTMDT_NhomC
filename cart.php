<?php
session_start();
include '../db.php';
if(!isset($_SESSION['user_id'])) header("Location: ../login.php");

$user_id = $_SESSION['user_id'];
$sql = "SELECT cart.id as cart_id, products.name, products.price, cart.quantity 
        FROM cart 
        JOIN products ON cart.product_id = products.id 
        WHERE cart.user_id = $user_id";
$result = $conn->query($sql);

if(isset($_POST['checkout'])){
    header("Location: checkout.php");
    exit;
}
?>

<h2>Giỏ hàng</h2>
<form method="POST">
<table>
<tr><th>Sản phẩm</th><th>Giá</th><th>Số lượng</th></tr>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['price']; ?></td>
    <td><?php echo $row['quantity']; ?></td>
</tr>
<?php endwhile; ?>
</table>
<button type="submit" name="checkout">Đặt hàng</button>
</form>

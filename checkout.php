<?php
session_start();
include '../db.php';
if(!isset($_SESSION['user_id'])) header("Location: ../login.php");

$user_id = $_SESSION['user_id'];

if(isset($_POST['place_order'])){
    $address = $_POST['address'];
    $payment = $_POST['payment'];

    // Thêm order
    $stmt = $conn->prepare("INSERT INTO orders(user_id,address,payment,status) VALUES(?,?,?,?)");
    $status = 'Pending';
    $stmt->bind_param("isss",$user_id,$address,$payment,$status);
    $stmt->execute();

    // Lấy order_id vừa tạo
    $order_id = $conn->insert_id;

    // Chuyển cart sang order_items
    $sql = "SELECT * FROM cart WHERE user_id=$user_id";
    $cart_items = $conn->query($sql);
    while($item = $cart_items->fetch_assoc()){
        $stmt = $conn->prepare("INSERT INTO order_items(order_id, product_id, quantity) VALUES(?,?,?)");
        $stmt->bind_param("iii",$order_id,$item['product_id'],$item['quantity']);
        $stmt->execute();
    }

    // Xóa cart
    $conn->query("DELETE FROM cart WHERE user_id=$user_id");

    echo "Đặt hàng thành công!";
}
?>

<h2>Checkout</h2>
<form method="POST">
    <input type="text" name="address" placeholder="Địa chỉ nhận hàng" required />
    <select name="payment" required>
        <option value="Cash">Tiền mặt</option>
        <option value="Card">Thẻ</option>
    </select>
    <button type="submit" name="place_order">Đặt hàng</button>
</form>

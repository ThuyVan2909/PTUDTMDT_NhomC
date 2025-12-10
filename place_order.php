<?php
session_start();
$conn = new mysqli("localhost","root","","lendly_db");

$cart = $_SESSION['cart'];
$total = 0;

foreach ($cart as $i) {
    $sku = $i['sku_id'];
    $qty = $i['quantity'];
    $price = $conn->query("SELECT price FROM sku WHERE id=$sku")->fetch_assoc()['price'];
    $total += $price * $qty;
}

$user_id = $_SESSION['user_id'] ?? null;

$stmt = $conn->prepare("
INSERT INTO orders (user_id,total,status,fullname,phone,address)
VALUES (?,?,?,?,?,?)
");
$status = "pending";
$stmt->bind_param("idssss",
    $user_id, $total, $status,
    $_POST['fullname'], $_POST['phone'], $_POST['address']
);
$stmt->execute();
$order_id = $stmt->insert_id;

// Items + giảm tồn kho
foreach ($cart as $i) {
    $sku = $i['sku_id'];
    $qty = $i['quantity'];
    $price = $conn->query("SELECT price FROM sku WHERE id=$sku")->fetch_assoc()['price'];

    $conn->query("INSERT INTO order_items(order_id,sku_id,quantity,price)
                  VALUES($order_id,$sku,$qty,$price)");

    $conn->query("UPDATE sku SET stock = stock - $qty WHERE id=$sku");
}

$conn->query("INSERT INTO order_tracking(order_id,status,note)
              VALUES($order_id,'pending','Đơn hàng đã được tạo')");

unset($_SESSION['cart']);

header("Location: order_success.php?id=$order_id");

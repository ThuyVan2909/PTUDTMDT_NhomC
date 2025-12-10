<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "Giỏ hàng trống";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin từ form
    $fullname = trim($_POST['fullname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $province_id = $_POST['province_id'] ?: null;
    $district_id = $_POST['district_id'] ?: null;
    $street = trim($_POST['street'] ?? '');
    $payment_method = $_POST['payment_method'] ?? 'cod';
    $shipping_method = $_POST['shipping_method'] ?? 'standard';

    if (!$fullname || !$phone || !$province_id || !$district_id) {
        echo "Vui lòng điền đầy đủ thông tin giao hàng";
        exit;
    }

    // Tính tổng
    $total = 0;
    foreach ($cart as $k => $it) {
        $sku_id = $it['sku_id'];
        $p = $conn->query("SELECT price FROM sku WHERE id=$sku_id")->fetch_assoc();
        if (!$p) {
            unset($cart[$k]);
            continue;
        }
        $cart[$k]['price'] = $p['price'];
        $total += $p['price'] * $it['quantity'];
    }

    if (empty($cart)) {
        echo "Giỏ hàng trống";
        exit;
    }

    // Tạo đơn hàng
    $stmt = $conn->prepare("
        INSERT INTO orders(
            user_id, total, status, fullname, phone,
            payment_method, shipping_method,
            province_id, district_id, street, created_at
        ) VALUES (?, ?, 'Đang chờ', ?, ?, ?, ?, ?, ?, ?, NOW())
    ");

    // Chuyển về đúng kiểu dữ liệu cho bind_param
    $user_id_param = $user_id ?: null;
    $province_id_param = intval($province_id);
    $district_id_param = intval($district_id);

    $stmt->bind_param(
        "idsssiiss",
        $user_id_param,
        $total,
        $fullname,
        $phone,
        $payment_method,
        $shipping_method,
        $province_id_param,
        $district_id_param,
        $street
    );
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Lưu order_items và trừ stock
    foreach ($cart as $it) {
        $stmt2 = $conn->prepare("
            INSERT INTO order_items(order_id, sku_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        $stmt2->bind_param("iiid", $order_id, $it['sku_id'], $it['quantity'], $it['price']);
        $stmt2->execute();

        $stmt3 = $conn->prepare("UPDATE sku SET stock = stock - ? WHERE id = ?");
        $stmt3->bind_param("ii", $it['quantity'], $it['sku_id']);
        $stmt3->execute();
    }

    unset($_SESSION['cart']);

    // Tracking
    $stmt4 = $conn->prepare("
        INSERT INTO order_tracking(order_id, status, note, updated_at)
        VALUES (?, 'Đang chờ', 'Đơn hàng đã được tạo', NOW())
    ");
    $stmt4->bind_param("i", $order_id);
    $stmt4->execute();

    echo "Đặt hàng thành công! Mã đơn: $order_id";
    exit;
}

// Lấy danh sách tỉnh/thành
$provinces = $conn->query("SELECT id, name FROM provinces ORDER BY name")->fetch_all(MYSQLI_ASSOC);
?>

<h2>Thanh toán</h2>
<form method="POST">
    <label>Họ tên</label>
    <input type="text" name="fullname" required>

    <label>Số điện thoại</label>
    <input type="text" name="phone" required>

    <label>Tỉnh/Thành phố</label>
    <select name="province_id" id="province" required>
        <option value="">--Chọn tỉnh--</option>
        <?php foreach ($provinces as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Quận/Huyện</label>
    <select name="district_id" id="district" required>
        <option value="">--Chọn quận/huyện--</option>
    </select>

    <label>Đường/Số nhà</label>
    <input type="text" name="street">

    <label>Phương thức thanh toán</label>
    <select name="payment_method">
        <option value="cod">COD</option>
        <option value="bank">Chuyển khoản</option>
    </select>

    <label>Vận chuyển</label>
    <select name="shipping_method">
        <option value="standard">Tiêu chuẩn</option>
        <option value="express">Hỏa tốc</option>
    </select>

    <button type="submit">Thanh toán</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$('#province').change(function(){
    var province_id = $(this).val();
    if(!province_id) {
        $('#district').html('<option value="">--Chọn quận/huyện--</option>');
        return;
    }
    $.get('get_districts.php', {province_id: province_id}, function(data){
        $('#district').html(data);
    });
});
</script>

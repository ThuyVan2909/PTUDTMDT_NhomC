<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    echo "Giỏ hàng trống";
    exit;
}

/* Lấy địa chỉ mặc định */
$default = null;
if ($user_id) {
    $stmt = $conn->prepare("
        SELECT fullname, phone, province_id, district_id, street 
        FROM users WHERE id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $default = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $province_id = intval($_POST['province_id']);
    $district_id = intval($_POST['district_id']);
    $street = trim($_POST['street']);
    $payment_method = $_POST['payment_method'] ?? 'cod';
    $shipping_method = $_POST['shipping_method'] ?? 'standard';

    if (!$fullname || !$phone || !$province_id || !$district_id) {
        echo "Vui lòng điền đầy đủ thông tin giao hàng";
        exit;
    }

    /* --------------------------------------------
       1. TÍNH TỔNG GIÁ GIỎ HÀNG
       -------------------------------------------- */
    $total = 0.0;
    foreach ($cart as $k => $it) {
        $sku_id = intval($it['sku_id']);
        $priceRow = $conn->query("SELECT price FROM sku WHERE id=$sku_id");
        if (!$priceRow) continue;

        $price = (float)$priceRow->fetch_assoc()['price'];
        $qty = intval($it['quantity']);

        $total += $price * $qty;
        $cart[$k]['price'] = $price; // gắn vào mảng để dùng sau
    }

    if ($total <= 0) {
        echo "Lỗi giỏ hàng";
        exit;
    }

    /* --------------------------------------------
       2. LẤY MÃ GIẢM GIÁ (NẾU CÓ)
       -------------------------------------------- */
    $discount_total = 0;
    if (isset($_SESSION['coupon'])) {
        $discount_total = floatval($_SESSION['coupon']['discount']);
    }

    // Không cho giảm quá tổng
    $discount_total = min($discount_total, $total);

    // Tổng thanh toán cuối
    $final_price = $total - $discount_total;


    /* --------------------------------------------
       3. INSERT ĐƠN HÀNG
       -------------------------------------------- */
    /* --------------------------------------------
   3. INSERT ĐƠN HÀNG
   -------------------------------------------- */
$sql = "
    INSERT INTO orders(
        user_id, total, discount, final_price, status,
        payment_method, shipping_method,
        fullname, phone,
        province_id, district_id, street,
        coupon_code, coupon_discount,
        created_at
    )
    VALUES (?, ?, ?, ?, 'Đã đặt', ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
";

// Khởi tạo stmt từ sql
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Bây giờ mới bind_param
$stmt->bind_param(
    "idddsssssiiss",
    $user_id,
    $total,
    $discount_total,
    $final_price,
    $payment_method,
    $shipping_method,
    $fullname,
    $phone,
    $province_id,
    $district_id,
    $street,
    $coupon_code,
    $discount_total
);


if (!$stmt->execute()) {
    die("Execute failed (insert order): " . $stmt->error);
}
$order_id = $stmt->insert_id;



    /* --------------------------------------------
       4. INSERT ORDER ITEMS + PHÂN BỔ GIẢM GIÁ
       -------------------------------------------- */
    foreach ($cart as $it) {

        $skuId = intval($it['sku_id']);
        $qty = intval($it['quantity']);
        $price = floatval($it['price']);

        $subtotal = $price * $qty;

        // Tỷ lệ phân bổ giảm giá
        $item_discount = 0;
        if ($discount_total > 0) {
            $ratio = $subtotal / $total;
            $item_discount = round($discount_total * $ratio);
        }

        /* Lưu item kèm giảm giá */
        $stmt2 = $conn->prepare("
            INSERT INTO order_items(order_id, sku_id, quantity, price, discount_amount)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt2->bind_param("iiidi", $order_id, $skuId, $qty, $price, $item_discount);
        $stmt2->execute();

        /* Trừ tồn kho */
        $stmt3 = $conn->prepare("UPDATE sku SET stock = stock - ? WHERE id = ?");
        $stmt3->bind_param("ii", $qty, $skuId);
        $stmt3->execute();
    }


    /* --------------------------------------------
       5. THÊM TRACKING
       -------------------------------------------- */
    $stmt4 = $conn->prepare("
        INSERT INTO order_tracking(order_id, status, note, updated_at)
        VALUES (?, 'Đã đặt', 'Đơn hàng đã được tạo', NOW())
    ");
    $stmt4->bind_param("i", $order_id);
    $stmt4->execute();


    /* --------------------------------------------
       6. RESET GIỎ + CHUYỂN TRANG
       -------------------------------------------- */
    unset($_SESSION['cart']);
    unset($_SESSION['coupon']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ... (tính tổng, áp dụng coupon, insert orders như trước)

    // Lưu đơn xong, reset giỏ hàng
    unset($_SESSION['cart']);
    unset($_SESSION['coupon']);

    // Trả về JSON nếu là AJAX
    if(isset($_POST['ajax']) && $_POST['ajax'] == 1){
        echo json_encode([
            'status' => true,
            'order_id' => $order_id,
            'message' => 'Thanh toán thành công!'
        ]);
        exit;
    }

    // Nếu không phải AJAX, vẫn redirect bình thường
    header("Location: order_success.php?id=$order_id");
    exit;
}

    exit;
}


/* Lấy danh sách tỉnh */
$provinces = $conn->query("SELECT id, name FROM provinces ORDER BY name")->fetch_all(MYSQLI_ASSOC);
?>


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thanh toán</title>
</head>
<body>

<h2>Thanh toán</h2>
<form method="POST" id="checkoutForm">
    <label>Họ tên</label><br>
    <input type="text" name="fullname" value="<?= htmlspecialchars($default['fullname'] ?? '') ?>" required><br><br>

    <label>Số điện thoại</label><br>
    <input type="text" name="phone" value="<?= htmlspecialchars($default['phone'] ?? '') ?>" required><br><br>

    <label>Tỉnh/Thành phố</label><br>
    <select name="province_id" id="province" required>
        <option value="">--Chọn tỉnh--</option>
        <?php foreach ($provinces as $p): ?>
            <option 
                value="<?= $p['id'] ?>"
                <?= (isset($default['province_id']) && $default['province_id'] == $p['id']) ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($p['name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Quận/Huyện</label><br>
    <select name="district_id" id="district" required>
        <option value="">--Chọn quận/huyện--</option>
    </select><br><br>

    <label>Đường/Số nhà</label><br>
    <input type="text" name="street" value="<?= htmlspecialchars($default['street'] ?? '') ?>"><br><br>

    <label>Phương thức thanh toán</label><br>
    <select name="payment_method">
        <option value="cod">COD</option>
        <option value="bank">Chuyển khoản</option>
    </select><br><br>

    <label>Vận chuyển</label><br>
    <select name="shipping_method">
        <option value="standard">Tiêu chuẩn</option>
        <option value="express">Hỏa tốc</option>
    </select><br><br>

    <button type="submit" class="btn">Đặt hàng</button>
</form>
<div id="successPopup" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; padding:30px; border-radius:10px; max-width:400px; text-align:center;">
        <h2 id="popupMessage" style="margin-bottom:40px;"></h2>
        <p>Đơn hàng của bạn: <span id="popupOrderId"></span></p>
        <button onclick="window.location='index.php'" class="btn">Về trang chủ</button>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function loadDistricts(pid, selectedDistrict = null) {
    if (!pid) {
        $('#district').html('<option value="">--Chọn quận/huyện--</option>');
        return;
    }

    $.get('get_districts.php', {province_id: pid}, function(data){
        $('#district').html(data);

        if (selectedDistrict) {
            $('#district').val(selectedDistrict);
        }
    });
}

// Khi chọn tỉnh
$('#province').change(function(){
    loadDistricts($(this).val());
});

// Load huyện ngay khi load trang (nếu user có địa chỉ sẵn)
<?php if (!empty($default['province_id'])): ?>
    loadDistricts(
        <?= (int)$default['province_id'] ?>,
        <?= !empty($default['district_id']) ? (int)$default['district_id'] : 'null' ?>
    );
<?php endif; ?>


</script>

<script>
$('#checkoutForm').submit(function(e){
    e.preventDefault();

    var formData = $(this).serialize() + '&ajax=1';

    $.post('checkout.php', formData, function(res){
        if(res.status){
            $('#popupMessage').text(res.message);
            $('#popupOrderId').text(res.order_id);
            $('#successPopup').css('display','flex');
        } else {
            alert(res.message || 'Thanh toán thất bại');
        }
    }, 'json');
});
</script>


</body>
</html>

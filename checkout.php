<?php
session_start();

include 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
$cart = $_SESSION['cart'] ?? [];
// ==========================
// TÍNH TỔNG GIỎ HÀNG (CHO HIỂN THỊ)
// ==========================
$total = 0;
foreach ($cart as $it) {
    $sku_id = intval($it['sku_id']);
    $priceRow = $conn->query("SELECT price FROM sku WHERE id=$sku_id");
    if (!$priceRow) continue;

    $price = (float)$priceRow->fetch_assoc()['price'];
    $qty = intval($it['quantity']);

    $total += $price * $qty;
}
if (!$user_id) {
    // Nếu gọi AJAX → trả JSON cho JS bật popup login
    if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {
        echo json_encode([
            'status' => false,
            'need_login' => true,
            'message' => 'Bạn cần đăng nhập để thanh toán'
        ]);
        exit;
    }

    // Nếu truy cập trực tiếp URL /checkout.php  
    // => Hiện popup login (không redirect)
    echo "<script>window.location='login.php';</script>";
    exit;
}


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
    $conn->begin_transaction();
    try {

    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $province_id = intval($_POST['province_id']);
    $district_id = intval($_POST['district_id']);
    $street = trim($_POST['street']);
    $payment_method = $_POST['payment_method'] ?? 'cod';
    $shipping_method = $_POST['shipping_method'] ?? 'standard';

    if (!$fullname || !$phone || !$province_id || !$district_id) {
        throw new Exception("Vui lòng điền đầy đủ thông tin giao hàng");
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
        throw new Exception("Lỗi giỏ hàng");
    }

    /* --------------------------------------------
       2. LẤY MÃ GIẢM GIÁ (NẾU CÓ)
       -------------------------------------------- */
    $discount_total = $_SESSION['coupon_discount'] ?? 0;
$coupon_code    = $_SESSION['coupon_code'] ?? null;


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
        // LOCK + CHECK tồn kho
        $stmtCheck = $conn->prepare("
        SELECT stock 
        FROM sku 
        WHERE id = ? 
        FOR UPDATE
        ");
        $stmtCheck->bind_param("i", $skuId);
        $stmtCheck->execute();
        $row = $stmtCheck->get_result()->fetch_assoc();

if (!$row) {
    throw new Exception("SKU không tồn tại");
}

if ($row['stock'] < $qty) {
    throw new Exception("Sản phẩm không đủ tồn kho");
}
$stmtStock = $conn->prepare("
    UPDATE sku 
    SET stock = stock - ? 
    WHERE id = ?
");
$stmtStock->bind_param("ii", $qty, $skuId);
$stmtStock->execute();

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


    $conn->commit();

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
} catch (Exception $e) {
    $conn->rollback();

    if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {
        echo json_encode([
            'status' => false,
            'message' => $e->getMessage()
        ]);
    } else {
        echo $e->getMessage();
    }
    exit;
}

    exit;
}


/* Lấy danh sách tỉnh */
$provinces = $conn->query("SELECT id, name FROM provinces ORDER BY name")->fetch_all(MYSQLI_ASSOC);
?>




<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Thanh toán</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
        }
        .checkout-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,.05);
        }
        .form-label {
            font-weight: 500;
        }
        .btn-primary {
            background: #1A3D64;
            border-color: #1A3D64;
        }
        .btn-primary:hover {
            background: #143252;
            border-color: #143252;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .summary-total {
            font-size: 18px;
            font-weight: 700;
            color: #e30019;
        }
        .order-items {
    max-height: 360px;
    overflow-y: auto;
}
.order-items img {
    background: #f5f5f5;
}

    </style>
</head>

<body>

<?php include 'partials/header.php'; ?>


<?php include "breadcrumb.php"; ?>


<div class="container my-5">
    <h2 class="fw-bold mb-4">Thanh toán</h2>

    <form method="POST" id="checkoutForm">
        <div class="row g-4">

            <!-- LEFT: SHIPPING INFO -->
            <div class="col-lg-7">
                <div class="checkout-card p-4 mb-4">
                    <h5 class="mb-3 fw-bold">Thông tin giao hàng</h5>

                    <div class="mb-3">
                        <label class="form-label">Họ tên</label>
                        <input type="text" class="form-control" name="fullname"
                               value="<?= htmlspecialchars($default['fullname'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="phone"
                               value="<?= htmlspecialchars($default['phone'] ?? '') ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tỉnh / Thành phố</label>
                            <select name="province_id" id="province" class="form-select" required>
                                <option value="">-- Chọn tỉnh --</option>
                                <?php foreach ($provinces as $p): ?>
                                    <option
                                        value="<?= $p['id'] ?>"
                                        <?= (isset($default['province_id']) && $default['province_id'] == $p['id']) ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($p['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quận / Huyện</label>
                            <select name="district_id" id="district" class="form-select" required>
                                <option value="">-- Chọn quận/huyện --</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Đường / Số nhà</label>
                        <input type="text" class="form-control" name="street"
                               value="<?= htmlspecialchars($default['street'] ?? '') ?>">
                    </div>
                </div>

                <div class="checkout-card p-4">
                    <h5 class="mb-3 fw-bold">Phương thức</h5>

                    <div class="mb-3">
                        <label class="form-label">Thanh toán</label>
                        <select name="payment_method" class="form-select">
                            <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                            <option value="bank">Chuyển khoản ngân hàng</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Vận chuyển</label>
                        <select name="shipping_method" class="form-select">
                            <option value="standard">Tiêu chuẩn</option>
                            <option value="express">Hỏa tốc</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- RIGHT: ORDER SUMMARY -->
            <div class="col-lg-5">
                <div class="checkout-card p-4 sticky-top" style="top:20px;">
                    <h5 class="fw-bold mb-3">Tóm tắt đơn hàng</h5>
                    <?php
$finalTotal = 0;
?>

<div class="order-items mb-3">

<?php foreach ($cart as $item): ?>
    <?php
        $skuId = (int)$item['sku_id'];
        $qty   = (int)$item['quantity'];

        // Giá ưu tiên promo
        $price = (!empty($item['promo_price']) && $item['promo_price'] > 0)
            ? (float)$item['promo_price']
            : (float)$item['price'];

        $itemTotal = $price * $qty;
        $finalTotal += $itemTotal;

        // LẤY ẢNH SKU (ĐÚNG THEO BẢNG sku_images)
        $imgRow = $conn->query("
            SELECT image_url 
            FROM sku_images 
            WHERE sku_id = $skuId 
            ORDER BY is_primary DESC 
            LIMIT 1
        ")->fetch_assoc();

        $imgSrc = $imgRow['image_url'] ?? 'assets/images/no-image.png';
    ?>

    <div class="d-flex align-items-start mb-3">

        <img src="<?= htmlspecialchars($imgSrc) ?>"
             style="width:60px;height:60px;object-fit:cover;border-radius:8px;margin-right:12px;">

        <div class="flex-grow-1">
    <div class="fw-semibold">
        <?= htmlspecialchars($item['spu_name']) ?>
    </div>

    <?php if (!empty($item['attributes'])): ?>
        <div class="text-muted small">
            <?php foreach ($item['attributes'] as $attrName => $attrValue): ?>
                <div>
                    <?= htmlspecialchars($attrName) ?>:
                    <strong><?= htmlspecialchars($attrValue) ?></strong>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="text-muted small mt-1">
        Số lượng: x<?= $qty ?>
    </div>
</div>


        <div class="fw-bold text-danger text-end">
            <?= number_format($itemTotal) ?> đ
        </div>
    </div>

<?php endforeach; ?>

</div>

                    <div class="summary-row">
                        <span>Tạm tính</span>
                        <strong><?= number_format($finalTotal) ?> đ</strong>

                    </div>

                    <?php if (!empty($_SESSION['coupon']['discount'])): ?>
                        <div class="summary-row text-success">
                            <span>Giảm giá</span>
                            <strong>-<?= number_format($_SESSION['coupon']['discount']) ?> đ</strong>
                        </div>
                    <?php endif; ?>

                    <hr>

                    <div class="summary-row">
                        <span>Tổng thanh toán</span>
                        <span class="summary-total">
    <?= number_format($finalTotal - ($_SESSION['coupon']['discount'] ?? 0)) ?> đ
</span>

                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-4 py-2">
                        Đặt hàng
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<!-- SUCCESS POPUP -->
<div id="successPopup" style="
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,.5);
    z-index:9999;
    justify-content:center;
    align-items:center;">
    <div class="bg-white p-4 rounded text-center" style="max-width:400px;">
        <h4 id="popupMessage" class="mb-3"></h4>
        <p>Đơn hàng của bạn: <strong id="popupOrderId"></strong></p>
        <button onclick="window.location='index.php'" class="btn btn-primary mt-3">
            Về trang chủ
        </button>
    </div>
</div>

<!-- JS GIỮ NGUYÊN -->
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

$('#province').change(function(){
    loadDistricts($(this).val());
});

<?php if (!empty($default['province_id'])): ?>
loadDistricts(
    <?= (int)$default['province_id'] ?>,
    <?= !empty($default['district_id']) ? (int)$default['district_id'] : 'null' ?>
);
<?php endif; ?>

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
<?php include 'partials/footer.php'; ?>



</body>
</html>
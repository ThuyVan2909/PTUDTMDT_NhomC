<?php
session_start();
include 'db.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;

$breadcrumbs = [
    ["label" => "Trang chủ", "url" => "index.php"],
    ["label" => "Giỏ hàng"]
];

// include "breadcrumb.php";

// FIX: Nếu giỏ trống → reset coupon
if (empty($cart)) {
    unset($_SESSION['coupon_code']);
    unset($_SESSION['coupon_discount']);
    $coupon_code = "";
    $coupon_discount = 0;
} else {
    $coupon_code = $_SESSION['coupon_code'] ?? "";
    $coupon_discount = $_SESSION['coupon_discount'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Giỏ hàng</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<style>
body { font-family: Arial; background: #f7f7f7; margin:0; padding:0;}
.container { width: 1000px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
h2 { margin-bottom: 20px; }
table { width: 100%; border-collapse: collapse; }
th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; vertical-align: middle;}
th { background: #fafafa; }
.product-img { width: 80px; height: 80px; object-fit: cover; border-radius: 5px; }
.attr-list { font-size: 14px; color: #555; }
.price-old { text-decoration: line-through; color: gray; font-size: 14px; margin-left:5px; }
.qty-input { width: 50px; padding:5px; text-align:center; }
.total-row { font-weight: bold; font-size: 18px; text-align: right; }
.btn { padding: 12px 20px; background:#e30019; color:#fff; border:none; border-radius:5px; cursor:pointer; font-size:16px; margin-top:20px; text-decoration:none; display:inline-block; }
.btn:hover { background:#c00; }

.coupon-box { margin-top: 20px; padding: 15px; background:#fafafa; border-radius:6px; }
.coupon-input { width:200px; padding:8px; }



/* ===== ANNOUNCEMENT BAR ===== */
.announcement-bar {
    background: #1A3D64; /* xanh TechZone */
    color: #fff;
    font-size: 14px;
    font-weight: 500;
    overflow: hidden;
    white-space: nowrap;
}

.announcement-track {
    display: inline-flex;
    align-items: center;
    gap: 48px;
    padding: 8px 0;
    animation: marquee 18s linear infinite;
}

.announcement-track span {
    display: inline-block;
}

@keyframes marquee {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}


/* ===== FOOTER ===== */
.footer {
    background: linear-gradient(90deg, #EEF4FA, #F8FAFC);
    border-top: 1px solid #E2E8F0;
}

.footer h6 {
    color: #0F172A;
}

.footer-links li {
    margin-bottom: 8px;
}

.footer-links a {
    color: #475569;
    text-decoration: none;
    transition: 0.2s;
}

.footer-links a:hover {
    color: #1A3D64;
}

.social-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #E2E8F0;
    color: #1A3D64;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: 0.2s;
}

.social-icon:hover {
    background: #1A3D64;
    color: #fff;
}



</style>
</head>
<body>

<!-- ANNOUNCEMENT BAR -->
<div class="announcement-bar">
  <div class="announcement-track">
    <span>Thu cũ giá ngon – Lên đời tiết kiệm</span>
    <span>Sản phẩm chính hãng – Xuất VAT đầy đủ</span>
    <span>Giao nhanh – Miễn phí cho đơn 300k</span>

    <!-- duplicate để chạy mượt -->
    <span>Thu cũ giá ngon – Lên đời tiết kiệm</span>
    <span>Sản phẩm chính hãng – Xuất VAT đầy đủ</span>
    <span>Giao nhanh – Miễn phí cho đơn 300k</span>
  </div>
</div>

<h2> header </h2>


<?php include "breadcrumb.php"; ?>


<div class="container">
<h2>Giỏ hàng</h2>
<table id="cartTable">
<tr>
    <th>Ảnh sản phẩm</th>
    <th>Tên sản phẩm</th>
    <th>Giá</th>
    <th>Số lượng</th>
    <th>Tổng</th>
</tr>

<?php if(!empty($cart)):
    foreach($cart as $item):
        $price = $item['promo_price'] ?? $item['price'];
        $subtotal = $price * $item['quantity'];
        $total += $subtotal;

        $sku_id = intval($item['sku_id']);
        $img_query = $conn->query("SELECT image_url FROM sku_images WHERE sku_id=$sku_id AND is_primary=1 LIMIT 1");
        $img_data = $img_query->fetch_assoc();
        $img_url = $img_data ? $img_data['image_url'] : '/techzone/assets/images/no-image.png';
?>
<tr>
    <td><img src="<?= htmlspecialchars($img_url) ?>" class="product-img"></td>
    <td>
        <strong><?= htmlspecialchars($item['spu_name']) ?></strong><br>
        <div class="attr-list">
            <?php if(!empty($item['attributes'])): ?>
                <?php foreach($item['attributes'] as $k=>$v): ?>
                    <?= htmlspecialchars($k.": ".$v) ?><br>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </td>
    <td>
        <span style="color:#e30019; font-weight:bold;"><?= number_format($item['promo_price']) ?> đ</span>
        <?php if($item['price'] != $item['promo_price']): ?>
            <span class="price-old"><?= number_format($item['price']) ?> đ</span>
        <?php endif; ?>
    </td>
    <td>
        <input type="number" class="qty-input" min="1" value="<?= $item['quantity'] ?>" 
               onchange="updateQty(<?= $item['sku_id'] ?>, this.value)">
    </td>
    <td>
    <?= number_format($subtotal) ?> đ
    <br>
    <a href="javascript:void(0)" onclick="removeItem(<?= $item['sku_id'] ?>)" 
       style="color:red; font-size:13px; text-decoration:none;">
        Xóa
    </a>
</td>

</tr>
<?php endforeach; else: ?>
<tr><td colspan="5" style="text-align:center;">Giỏ hàng trống</td></tr>
<?php endif; ?>

<!-- Tổng tạm tính -->
<tr>
    <td colspan="4" class="total-row">Tạm tính:</td>
    <td class="total-row" id="subtotalDisplay"><?= number_format($total) ?> đ</td>
</tr>

<!-- Giảm giá -->
<tr id="discountRow" style="<?= $coupon_discount > 0 ? '' : 'display:none;' ?>">
    <td colspan="4" class="total-row" style="color:green">
        Giảm giá (<span id="couponCode"><?= htmlspecialchars($coupon_code) ?></span>):
    </td>
    <td class="total-row" style="color:green" id="discountDisplay">
        -<?= number_format($coupon_discount) ?> đ
    </td>
</tr>

<!-- Tổng thanh toán -->
<tr id="finalRow" style="<?= $coupon_discount > 0 ? '' : 'display:none;' ?>">
    <td colspan="4" class="total-row">Tổng thanh toán:</td>
    <td class="total-row" id="finalTotal">
        <?= number_format($total - $coupon_discount) ?> đ
    </td>
</tr>

</table>

<!-- Nhập mã giảm giá -->
<div class="coupon-box">
    <label>Mã giảm giá:</label>
    <input type="text" id="coupon_code" name="coupon_code" class="coupon-input" value="<?= htmlspecialchars($coupon_code) ?>">
    <button class="btn" onclick="applyCoupon()">Áp dụng</button>
</div>

<?php if (!empty($cart)): ?>
    <?php if (!isset($_SESSION['user_id'])): ?>
    <button onclick="showNeedLogin()" class="btn">Thanh toán</button>
<?php else: ?>
    <a href="checkout.php" class="btn">Thanh toán</a>
<?php endif; ?>

<?php endif; ?>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// ==========================
// CẬP NHẬT SỐ LƯỢNG SKU
// ==========================
function updateQty(sku, qty){
    $.post("update_cart.php", { sku_id: sku, quantity: qty }, function(res){

        // Update subtotal
        $("#subtotalDisplay").text(res.subtotal.toLocaleString() + " đ");

        // Nếu đã có voucher trước đó → update final total
        if(res.discount > 0){
            $("#discountRow").show();
            $("#discountDisplay").text("-" + res.discount.toLocaleString() + " đ");

            $("#finalRow").show();
            $("#finalTotal").text(res.final_total.toLocaleString() + " đ");
        }

    }, "json");
}

// ==========================
// ÁP DỤNG COUPON
// ==========================
function applyCoupon(){
    let code = $("#coupon_code").val();

    $.post("apply_coupon.php", { coupon_code: code }, function(res){

        if(!res.status){
            alert(res.message);
            return;
        }

        // Hiện giảm giá
        $("#discountRow").show();
        $("#discountDisplay").text("-" + res.discount.toLocaleString() + " đ");

        $("#couponCode").text(code);

        // Hiện tổng sau giảm
        $("#finalRow").show();
        $("#finalTotal").text(res.total_after_discount.toLocaleString() + " đ");

        alert(res.message);

    }, "json");
}

function removeItem(sku){
    $.post("remove_item.php", { sku_id: sku }, function(res){
        res = JSON.parse(res);
        if(res.status){
            location.reload();
        } else {
            alert("Không thể xoá sản phẩm.");
        }
    });
}
</script>


</div>

<!-- POPUP YÊU CẦU ĐĂNG NHẬP -->
<div id="needLoginPopup" style="
    display:none; position:fixed; top:0; left:0;
    width:100%; height:100%; background:rgba(0,0,0,0.6);
    z-index:99999; justify-content:center; align-items:center;">
    
    <div style="background:#fff; padding:25px; border-radius:12px;
        width:90%; max-width:380px; text-align:center;">
        
        <h2 style="margin-bottom:15px; color:#333;">Bạn cần đăng nhập</h2>
        <p style="margin-bottom:20px;">Vui lòng đăng nhập để tiếp tục thanh toán.</p>

        <!-- CHỈ GỌI POPUP LOGIN, KHÔNG REDIRECT -->
        <button onclick="openLogin(); closeNeedLogin();" class="btn" 
                style="padding:10px 20px; display:inline-block;">
            Đăng nhập ngay
        </button>

        <br><br>
        <button onclick="closeNeedLogin()" 
                style="border:none; background:none; cursor:pointer; color:#888;">
            Đóng
        </button>
    </div>
</div>

<script>
function showNeedLogin(){
    document.getElementById('needLoginPopup').style.display = 'flex';
}
function closeNeedLogin(){
    document.getElementById('needLoginPopup').style.display = 'none';
}
</script>

<script>
function openLogin() {
    window.location.href = "login.php";
}
</script>


<!-- FOOTER -->
<footer class="footer mt-5">
  <div class="container py-5">
    <div class="row g-4">

      <!-- COL 1: BRAND -->
      <div class="col-lg-4 col-md-6">
        <div class="d-flex align-items-center gap-2 mb-3">
          <img src="assets/images/logo.png" alt="TechZone" height="36">

        </div>
        <p class="text-muted mb-2">
          Hệ thống bán lẻ thiết bị công nghệ chính hãng: Laptop, Điện thoại,
          Phụ kiện – Giá tốt, bảo hành minh bạch.
        </p>
        <small class="text-muted">
          © 2025 TechZone. All rights reserved.
        </small>
      </div>

      <!-- COL 2: POLICY -->
      <div class="col-lg-2 col-md-6">
        <h6 class="fw-semibold mb-3">Chính sách</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="#">Chính sách bảo hành</a></li>
          <li><a href="#">Chính sách đổi trả</a></li>
          <li><a href="#">Chính sách vận chuyển</a></li>
          <li><a href="#">Chính sách thanh toán</a></li>
        </ul>
      </div>

      <!-- COL 3: SUPPORT -->
      <div class="col-lg-3 col-md-6">
        <h6 class="fw-semibold mb-3">Hỗ trợ khách hàng</h6>
        <ul class="list-unstyled footer-links">
          <li>Hotline: <strong>1800 9999</strong></li>
          <li>Email: support@techzone.vn</li>
          <li><a href="#">Câu hỏi thường gặp (FAQ)</a></li>
          <li><a href="#">Liên hệ</a></li>
        </ul>
      </div>

      <!-- COL 4: SOCIAL -->
      <div class="col-lg-3 col-md-6">
        <h6 class="fw-semibold mb-3">Kết nối với TechZone</h6>
        <div class="d-flex gap-3 mb-3">
        
          <a href="https://facebook.com/techzone" 
            class="social-icon" 
            target="_blank"
            aria-label="Facebook TechZone">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
            </svg>
            </a>
            
            <a href="#" 
            class="social-icon" 
            target="_blank"
            aria-label="Instagram TechZone">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
            </svg>
            </a>


            <a href="#" 
            class="social-icon" 
            target="_blank"
            aria-label="Youtube TechZone">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
            <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.01 2.01 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.01 2.01 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31 31 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.01 2.01 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A100 100 0 0 1 7.858 2zM6.4 5.209v4.818l4.157-2.408z"/>
            </svg>
            </a>

        </div>

        <div>
          <small class="text-muted d-block mb-2">Phương thức thanh toán</small>
          <div class="d-flex gap-2">
            <img src="assets\images\Zalopay.png" height="26">
            <img src="assets\images\Apple_Pay_logo.svg.png" height="26">
            <img src="assets\images\Logo-VNPAY-QR-1.webp" height="26">
          </div>
        </div>
      </div>

    </div>
  </div>
</footer>


</body>
</html>

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
<link rel="icon" type="image/png" href="assets/images/icon_logo.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/header.css">
<link rel="stylesheet" href="assets/css/footer.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<style>
body { font-family: Arial; background: #f5f6fa; margin:0; padding:0;}
.container1 { width: 1000px; margin: 50px auto; background: #f5f6fa; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
h2 { margin-bottom: 20px; }
table { width: 100%; border-collapse: collapse; background: #ffff }
th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; vertical-align: middle;}
th { background: #f5f6fa; }
.product-img { width: 80px; height: 80px; object-fit: cover; border-radius: 5px; }
.attr-list { font-size: 14px; color: #555; }
.price-old { text-decoration: line-through; color: gray; font-size: 14px; margin-left:5px; }
.qty-input { width: 50px; padding:5px; text-align:center; }
.total-row { font-weight: bold; font-size: 18px; text-align: right;  }
/* .btn { padding: 12px 20px; background:#1A3D64; color:#fff; border:none; border-radius:5px; cursor:pointer; font-size:16px; margin-top:20px; text-decoration:none; display:inline-block; }
.btn:hover { background:#c00; } */

.btn {
    --bs-btn-color: #1A3D64;
    --bs-btn-border-color: #1A3D64;
    --bs-btn-hover-bg: #1A3D64;
    --bs-btn-hover-color: #fff;
}
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
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #f5f6fa;
}

/* ===== TABLE CARD ===== */
#cartTable {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
}

#cartTable th {
    background: #1A3D64;
    font-weight: 600;
    color: #e5e7eb;
    border-bottom: 1px solid #e5e7eb;
    
}

#cartTable td {
    border-bottom: 1px solid #f0f0f0;
}

#cartTable tr:last-child td {
    border-bottom: none;
}

/* ===== PRODUCT ===== */
.product-img {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    border: 1px solid #eee;
}

.attr-list {
    font-size: 13px;
    color: #6b7280;
}

/* ===== PRICE ===== */
.price-old {
    text-decoration: line-through;
    color: #9ca3af;
    font-size: 13px;
}

/* ===== TOTAL ROW ===== */
.total-row {
    font-size: 16px;
    font-weight: 600;
    
}

#finalTotal {
    color: #e30019;
    font-size: 20px;
    font-weight: 700;
}

/* ===== QUANTITY ===== */
.qty-input {
    width: 60px;
    padding: 6px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
}

/* ===== COUPON + PAYMENT CARD ===== */
.coupon-box {
    background: #fff;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    position: relative;
    min-width: 420px;
    max-width: 520px;
}

/* Label */
.coupon-box label {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
    display: block;
}

/* Selector */
.coupon-selector {
    padding: 10px 14px;
    border: 1px solid #d1d5db;
    border-radius: 10px;
    background: #fff;
    color: #6b7280;
    cursor: pointer;
    text-align: left;
    font-size: 14px;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Mũi tên dropdown */
.coupon-selector::after {
    content: "▾";
    font-size: 14px;
    color: #9ca3af;
}

/* Hover */
.coupon-selector:hover {
    border-color: #1A3D64;
    background: #f8fbff;
}

/* Khi đã chọn mã */
.coupon-selector.active {
    border-color: #1A3D64;
    background: #f0f6ff;
    color: #1A3D64;
    font-weight: 600;
}

/* Text placeholder */
.coupon-placeholder {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    
}

/* ===== DROPDOWN POPUP ===== */
#couponPopup {
    display: none;
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    width: 100%;
    min-width: 420px;
    max-width: 520px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    z-index: 9999;
    padding: 10px;
    max-height: 320px;
    overflow-y: auto;
    animation: fadeIn 0.15s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-6px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ===== COUPON ITEM ===== */
#couponList > div {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 10px;
    margin-bottom: 10px;
    transition: all 0.2s ease;
    background: #fff;
}

#couponList > div:hover {
    border-color: #1A3D64;
    background: #f8fbff;
}

/* Code */
#couponList strong {
    font-size: 14px;
    color: #1A3D64;
}

/* Description */
#couponList small {
    display: block

}


/* ===== COUPON ITEM ===== */
/* ===== COUPON HORIZONTAL ITEM ===== */
.coupon-item-horizontal {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;

    width: 100%;
    padding: 12px 14px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    background: #fff;
    margin-bottom: 10px;

    transition: all 0.2s ease;
}

.coupon-item-horizontal:hover {
    border-color: #1A3D64;
    background: #f8fbff;
}

/* LEFT */
.coupon-left {
    display: flex;
    flex-direction: column;
    gap: 4px;
    flex: 1;
    min-width: 0;
}

.coupon-code {
    font-size: 14px;
    font-weight: 600;
    color: #1A3D64;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.coupon-meta {
    font-size: 12px;
    color: #6b7280;
    display: flex;
    gap: 6px;
    align-items: center;
    white-space: nowrap;
}

.coupon-meta .divider {
    font-size: 10px;
}

/* RIGHT */
.coupon-right {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}

.coupon-discount {
    font-size: 14px;
    font-weight: 700;
    color: #e30019;
    white-space: nowrap;
}

/* APPLY BUTTON */
.coupon-apply-btn {
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 8px;
}

/* DISABLED */
.coupon-disabled-text {
    font-size: 12px;
    color: #9ca3af;
    white-space: nowrap;
}




/* ===== BUTTON ===== */
.btn {
    --bs-btn-color: #fff;
    --bs-btn-bg: #1A3D64;
    --bs-btn-border-color: #1A3D64;
    --bs-btn-hover-bg: #163355;
    --bs-btn-hover-border-color: #163355;
    font-weight: 600;
    border-radius: 10px;
}

/* ===== PAYMENT BUTTON ===== */
.payment-btn {
    margin-top: 16px;
    padding: 14px;
    font-size: 18px;
}

/* ===== EMPTY CART ===== */
.empty-cart {
    padding: 40px;
    text-align: center;
    color: #6b7280;
}


/* ===== SUBTOTAL (TỪNG SẢN PHẨM) ===== */
.item-subtotal {
    font-weight: 700;              /* đậm */
    color: #e30019;                /* đỏ chuẩn UI thương mại */
    font-size: 16px;
    white-space: nowrap;            /* không xuống dòng */
}


/* ===== MOBILE RESPONSIVE ===== */
@media (max-width: 768px) {
    .container {
        width: 95%;
        padding: 10px;
        margin: 20px auto;
    }

    #cartTable, #cartTable th, #cartTable td {
        display: block;
        width: 100%;
    }

    #cartTable tr {
        margin-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
        display: block;
    }

    #cartTable th {
        display: none; /* ẩn header */
    }

    #cartTable td {
        text-align: right;
        padding-left: 50%;
        position: relative;
        border-bottom: 1px solid #eee;
    }

    #cartTable td::before {
        content: attr(data-label);
        position: absolute;
        left: 15px;
        width: 45%;
        padding-left: 0;
        font-weight: 600;
        text-align: left;
    }

    .product-img {
        width: 60px;
        height: 60px;
    }

    .qty-input {
        width: 50px;
    }

    .coupon-box {
        min-width: auto;
        max-width: 100%;
        width: 100%;
        margin-bottom: 15px;
    }

    .btn.payment-btn {
        font-size: 16px;
        padding: 12px;
    }
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

<?php include 'partials/header.php'; ?>


<?php include "breadcrumb.php"; ?>


<div class="container my-5">
        <div class="row">
            <div class="col-12">
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

    <td class="item-subtotal" data-sku="<?= $item['sku_id'] ?>">
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
</div>
</div>

<div class="row mt-4">
    <div class="col-md-6 ms-auto">
<!-- Nhập mã giảm giá -->
   <!-- <div class="col-lg-4"> -->
    <div class="coupon-box coupon-wrapper">
    <label class="coupon-label">Mã giảm giá</label>

    <div id="couponSelector"
         class="coupon-selector <?= $coupon_code ? 'active' : '' ?>"
         onclick="openCouponPopup()">
        <span class="coupon-placeholder">
            <?= $coupon_code ? htmlspecialchars($coupon_code) : 'Chọn mã giảm giá' ?>
        </span>
    </div>

    
</div>




<?php if (!empty($cart)): ?>
    <?php if (!isset($_SESSION['user_id'])): ?>
    <button onclick="showNeedLogin()" class="btn payment-btn w-100">Thanh toán</button>
<?php else: ?>
    <a href="checkout.php" class="btn">Thanh toán</a>
<?php endif; ?>
  
<!-- <?php endif; ?> -->
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// ==========================
// CẬP NHẬT SỐ LƯỢNG SKU
// ==========================
function updateQty(sku, qty){
    $.post("update_cart.php", { sku_id: sku, quantity: qty }, function(res){

        if(!res.status) return;

        // ✅ UPDATE TỔNG CỦA DÒNG SKU
        $(".item-subtotal[data-sku='" + sku + "']")
            .contents()
            .first()
            .replaceWith(res.item_subtotal.toLocaleString() + " đ");

        // ✅ UPDATE TẠM TÍNH
        $("#subtotalDisplay").text(res.subtotal.toLocaleString() + " đ");

        // ✅ UPDATE GIẢM GIÁ + TỔNG CUỐI
        if(res.discount > 0){
            $("#discountRow").show();
            $("#discountDisplay").text("-" + res.discount.toLocaleString() + " đ");
            $("#finalRow").show();
            $("#finalTotal").text(res.final_total.toLocaleString() + " đ");
        } else {
            $("#discountRow").hide();
            $("#finalRow").hide();
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

<script>
// ==========================
// POPUP COUPON
// ==========================
function openCouponPopup() {
    const selector = document.getElementById('couponSelector');
    const popup = document.getElementById('couponPopup');

    // Lấy tọa độ selector
    const rect = selector.getBoundingClientRect();
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    popup.style.top = rect.bottom + scrollTop + "px";
    popup.style.left = rect.left + "px";
    popup.style.display = "block";

    fetch('get_available_coupons.php')
        .then(res => res.json())
        .then(data => {
            const list = document.getElementById('couponList');
            list.innerHTML = '';

            if (!data.coupons || data.coupons.length === 0) {
                list.innerHTML = '<p>Không có mã giảm giá khả dụng</p>';
                return;
            }

            data.coupons.forEach(c => {
                const disabled = c.eligible ? '' : 'opacity:0.5; pointer-events:none;';

                list.innerHTML += `
    <div class="coupon-item-horizontal" style="${disabled}">
        <div class="coupon-left">
            <div class="coupon-code">${c.code}</div>
            <div class="coupon-meta">
                <span>Đơn tối thiểu ${c.min_order.toLocaleString()} đ</span>
                <span class="divider">•</span>
                <span>HSD: ${c.expired_at ?? 'Không giới hạn'}</span>
            </div>
        </div>

        <div class="coupon-right">
            <div class="coupon-discount">-${c.discount.toLocaleString()}đ</div>

            ${c.eligible ? `
                <button class="btn btn-sm btn-primary coupon-apply-btn"
                    onclick="applyCouponFromPopup('${c.code}', ${c.discount})">
                    Áp dụng
                </button>
            ` : `
                <div class="coupon-disabled-text">${c.reason}</div>
            `}
        </div>
    </div>
`;


            });
        });
}

// Đóng popup khi click ra ngoài
document.addEventListener('click', function(e) {
    const popup = document.getElementById('couponPopup');
    const selector = document.getElementById('couponSelector');
    if (!popup.contains(e.target) && !selector.contains(e.target)) {
        popup.style.display = 'none';
    }
});


// ==========================
// ÁP DỤNG COUPON (KHÔNG VỠ LOGIC CŨ)
// ==========================
function applyCouponFromPopup(code) {
    $.post("apply_coupon.php", { coupon_code: code }, function(res) {

        if (!res.status) {
            alert(res.message);
            return;
        }

        // Cập nhật UI
        $("#couponSelector .coupon-placeholder").text(code);
        $("#couponSelector").addClass("active");

        $("#discountRow").show();
        $("#discountDisplay").text("-" + res.discount.toLocaleString() + " đ");
        $("#couponCode").text(code);

        $("#finalRow").show();
        $("#finalTotal").text(res.total_after_discount.toLocaleString() + " đ");

        $("#couponPopup").hide();

    }, "json");
}
</script>



<?php include 'partials/footer.php'; ?>


<!-- COUPON POPUP -->
<div id="couponPopup" style="
    display:none;
    position:absolute;
    background:#fff;
    border:1px solid #ddd;
    border-radius:8px;
    box-shadow:0 4px 12px rgba(0,0,0,0.15);
    width:250px;
    max-height:300px;
    overflow-y:auto;
    z-index:1000;
">
    <div style="padding:10px;" id="couponList"></div>
</div>

</body>
</html>

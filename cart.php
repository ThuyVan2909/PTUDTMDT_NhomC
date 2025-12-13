<?php
session_start();
include 'db.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;

// Giá trị mã giảm giá đang áp dụng
$coupon_code = $_SESSION['coupon_code'] ?? "";
$coupon_discount = $_SESSION['coupon_discount'] ?? 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Giỏ hàng</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
</style>
</head>
<body>

<div class="container">
<h2>Giỏ hàng</h2>
<table>
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

        // Lấy ảnh chính xác của SKU
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
        <input type="number" class="qty-input" min="1" value="<?= $item['quantity'] ?>" onchange="updateQty(<?= $item['sku_id'] ?>, this.value)">
    </td>
    <td><?= number_format($subtotal) ?> đ</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="5" style="text-align:center;">Giỏ hàng trống</td></tr>
<?php endif; ?>

<!-- Tổng tạm tính -->
<tr>
    <td colspan="4" class="total-row">Tạm tính:</td>
    <td class="total-row"><?= number_format($total) ?> đ</td>
</tr>

<!-- Nếu có mã giảm giá -->
<?php if($coupon_discount > 0): ?>
<tr>
    <td colspan="4" class="total-row" style="color:green">Giảm giá (<?= htmlspecialchars($coupon_code) ?>):</td>
    <td class="total-row" style="color:green">-<?= number_format($coupon_discount) ?> đ</td>
</tr>
<tr>
    <td colspan="4" class="total-row">Tổng thanh toán:</td>
    <td class="total-row"><?= number_format($total - $coupon_discount) ?> đ</td>
</tr>
<?php endif; ?>

</table>

<!-- Nhập mã giảm giá -->
<div class="coupon-box">
    <form method="post" action="apply_coupon.php">
        <label>Mã giảm giá:</label>
        <input type="text" name="coupon_code" class="coupon-input" value="<?= htmlspecialchars($coupon_code) ?>">
        <button class="btn">Áp dụng</button>
    </form>
</div>

<?php if(!empty($cart)): ?>
<a href="checkout.php" class="btn">Thanh toán</a>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function updateQty(sku, qty){
    $.post("update_cart.php", { sku_id: sku, quantity: qty }, function(){
        location.reload();
    });
}
</script>
</div>

</body>
</html>

<?php include 'partials/announcement-bar.php'; ?>
<?php include 'partials/header.php'; ?>
<?php
// session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
$breadcrumbs = [
    ["label" => "Trang chủ", "url" => "index.php"],
    ["label" => "Tài khoản"]
];

include "breadcrumb.php";
$conn = new mysqli("localhost", "root", "", "lendly_db");
$user_id = $_SESSION['user_id'];

/* Xử lý lưu cập nhật */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_profile'])) {

    $fullname = trim($_POST['fullname']);
    $gender   = $_POST['gender'];
    $phone    = trim($_POST['phone']);
    $email    = trim($_POST['email']);
    $birthday = $_POST['birthday'];

    $stmt = $conn->prepare("
        UPDATE users 
        SET fullname=?, gender=?, phone=?, email=?, birthday=? 
        WHERE id=?");
    $stmt->bind_param("sssssi", $fullname, $gender, $phone, $email, $birthday, $user_id);
    $stmt->execute();

    header("Location: account.php?tab=profile&updated=1");
    exit;
}

$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tài khoản - TechZone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/account.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
</head>

<body>
<!-- MAIN -->
<div class="flex-grow-1">


<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="sidebar">
    <div class="user-box mb-4 d-flex align-items-center">
        <div class="user-avatar">
            <?= strtoupper(substr($user['fullname'], 0, 1)) ?>
        </div>
        <div class="ms-3">
            <div class="user-name"><?= $user['fullname'] ?></div>
        </div>
    </div> <!-- ✅ ĐÓNG user-box -->

    <?php $tabActive = $_GET['tab'] ?? 'profile'; ?>

    <a href="?tab=profile" class="<?= $tabActive=='profile'?'active':'' ?>">
        Thông tin tài khoản
    </a>
    <a href="?tab=address" class="<?= $tabActive=='address'?'active':'' ?>">
        Địa chỉ
    </a>
    <a href="?tab=orders" class="<?= $tabActive=='orders'?'active':'' ?>">
        Quản lý đơn hàng
    </a>
    <a href="?tab=history" class="<?= $tabActive=='history'?'active':'' ?>">
        Sản phẩm đã xem
    </a>
    <a href="logout.php" class="logout">Đăng xuất</a>
</div>


    <!-- CONTENT -->
    <div class="flex-fill p-4">
        <div class="main-card page-transition">

<?php
$tab = $_GET['tab'] ?? 'profile';

if ($tab === 'profile') {

    if (isset($_GET['updated'])) {
        echo "<div class='alert alert-success'>Cập nhật thông tin thành công.</div>";
    }
    echo "<h3 class='fw-bold mb-4 text-primary-custom'>Thông tin tài khoản</h3>";
?>

<form method="POST">
    <input type="hidden" name="update_profile" value="1">

    <div class="mb-3">
        <label class="form-label">Họ tên</label>
        <input type="text" name="fullname" class="form-control" value="<?= $user['fullname'] ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Giới tính</label>
        <select name="gender" class="form-select">
            <option value="">-- Chọn --</option>
            <option value="Nam" <?= $user['gender']=='Nam'?'selected':'' ?>>Nam</option>
            <option value="Nữ" <?= $user['gender']=='Nữ'?'selected':'' ?>>Nữ</option>
            <option value="Khác" <?= $user['gender']=='Khác'?'selected':'' ?>>Khác</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Số điện thoại</label>
        <input type="text" name="phone" class="form-control" value="<?= $user['phone'] ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" required>
    </div>

    <div class="mb-4">
        <label class="form-label">Ngày sinh</label>
        <input type="date" name="birthday" class="form-control" value="<?= $user['birthday'] ?>">
    </div>

    <button class="btn btn-red">Lưu thông tin</button>
</form>

<?php
} elseif ($tab === 'address') {

$currentProvince = $user['province_id'];
$currentDistrict = $user['district_id'];
$currentStreet   = $user['street'];
?>

<h3 class="fw-bold mb-4 text-primary-custom">Địa chỉ</h3>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label class="form-label">Tỉnh / Thành phố</label>
        <select id="province" class="form-select"></select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Quận / Huyện</label>
        <select id="district" class="form-select"></select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Tên đường</label>
        <input type="text" id="street" class="form-control" value="<?= htmlspecialchars($currentStreet) ?>">
    </div>
</div>

<button id="saveAddress" class="btn btn-red">Lưu địa chỉ</button>

<div class="address-current">
    <h5>Địa chỉ hiện tại</h5>
    <p><strong>Tỉnh:</strong> <span id="textProvince"></span></p>
    <p><strong>Quận/Huyện:</strong> <span id="textDistrict"></span></p>
    <p><strong>Đường:</strong> <?= htmlspecialchars($currentStreet ?? "") ?></p>
</div>

<!-- JS GIỮ NGUYÊN -->
<script>
let currentProvince = "<?= $currentProvince ?>";
let currentDistrict = "<?= $currentDistrict ?>";

fetch("get_provinces.php")
.then(res=>res.json())
.then(data=>{
    let p=document.getElementById("province");
    let tp=document.getElementById("textProvince");
    data.forEach(x=>{
        let o=document.createElement("option");
        o.value=x.id;o.text=x.name;
        if(x.id==currentProvince){o.selected=true;tp.textContent=x.name;}
        p.appendChild(o);
    });
    if(currentProvince) loadDistricts(currentProvince,true);
});

function loadDistricts(pid,first=false){
    fetch("get_districts.php?province_id="+pid)
    .then(res=>res.text())
    .then(html=>{
        let d=document.getElementById("district");
        let td=document.getElementById("textDistrict");
        d.innerHTML=html;
        if(first && currentDistrict){
            d.value=currentDistrict;
            let s=d.options[d.selectedIndex];
            if(s) td.textContent=s.text;
        }
    });
}

document.getElementById("province").addEventListener("change",function(){
    loadDistricts(this.value,false);
});

document.getElementById("saveAddress").addEventListener("click",function(){
    let f=new FormData();
    f.append("province",province.value);
    f.append("district",district.value);
    f.append("street",street.value);
    fetch("save_address.php",{method:"POST",body:f})
    .then(r=>r.json())
    .then(d=>{alert(d.msg);location.reload();});
});
</script>

<?php
} elseif ($tab === 'orders') {

    echo "<h3 class='fw-bold mb-4 text-primary-custom'>Quản lý đơn hàng</h3>";

    $uid = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY id DESC");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        echo "<p>Bạn chưa có đơn hàng nào.</p>";
    } else {
        echo "<table class='table table-bordered'>";
        echo "<tr>
                <th>Mã đơn</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th></th>
              </tr>";

        while ($o = $res->fetch_assoc()) {
            echo "<tr>
                    <td>#{$o['id']}</td>
                    <td>" . number_format($o['total']) . "₫</td>
                    <td>{$o['status']}</td>
                    <td>{$o['created_at']}</td>
                    <td>
                        <a href='account.php?tab=order_detail&id={$o['id']}' class='btn-outline-primary-custom'>
                            Xem chi tiết
                        </a>
                    </td>
                  </tr>";
        }

        echo "</table>";
    }
        } elseif ($tab === 'order_detail') {

    $order_id = intval($_GET['id']);
    $uid = $_SESSION['user_id'];

    // Lấy thông tin đơn
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id=? AND user_id=? LIMIT 1");
    $stmt->bind_param("ii", $order_id, $uid);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();

    if (!$order) {
        echo "<p>Không tìm thấy đơn hàng.</p>";
        return;
    }

    echo "<h3 class='fw-bold mb-3'>Chi tiết đơn hàng #$order_id</h3>";
echo "<p><strong>Trạng thái:</strong> " . htmlspecialchars($order['status']) . "</p>";
echo "<p><strong>Tổng tiền:</strong> " . number_format($order['total']) . "₫</p>";
echo "<p><strong>Ngày tạo:</strong> {$order['created_at']}</p>";

$statusSteps = [
    'Đã đặt' => 1,
    'Người bán đang chuẩn bị hàng' => 2,
    'Đơn vị giao hàng đã nhận hàng' => 3,
    'Hàng đang giao đến nhà bạn' => 4,
    'Đơn hàng đã giao' => 5
];

$currentStep = $statusSteps[$order['status']] ?? 1;
$isCanceled = ($order['status'] === 'Đơn bị huỷ');
?>
<?php if ($isCanceled): ?>
    <div class="alert alert-danger fw-semibold mt-3">
        Đơn hàng đã bị huỷ
    </div>
<?php else: ?>
    <div class="order-tracking mt-4 mb-4">
        <div class="tracking-steps">
            <div class="step <?= $currentStep >= 1 ? 'active' : '' ?>">
                <div class="circle">1</div>
                <div class="label">Đã đặt</div>
            </div>
            <div class="step <?= $currentStep >= 2 ? 'active' : '' ?>">
                <div class="circle">2</div>
                <div class="label">Chuẩn bị hàng</div>
            </div>
            <div class="step <?= $currentStep >= 3 ? 'active' : '' ?>">
                <div class="circle">3</div>
                <div class="label">Đã giao ĐVVC</div>
            </div>
            <div class="step <?= $currentStep >= 4 ? 'active' : '' ?>">
                <div class="circle">4</div>
                <div class="label">Đang giao</div>
            </div>
            <div class="step <?= $currentStep >= 5 ? 'active' : '' ?>">
                <div class="circle">5</div>
                <div class="label">Hoàn tất</div>
            </div>
        </div>
    </div>
<?php endif; ?>



<?php
$stmt = $conn->prepare("
    SELECT 
        oi.*,
        s.variant,
        p.name AS product_name,
        si.image_url
    FROM order_items oi
    JOIN sku s ON oi.sku_id = s.id
    JOIN spu p ON s.spu_id = p.id
    LEFT JOIN sku_images si 
        ON si.sku_id = s.id AND si.is_primary = 1
    WHERE oi.order_id = ?
");

$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result();


    echo "<h5 class='mt-4'>Sản phẩm trong đơn</h5>";
    echo "<table class='table table-bordered'>";
echo "<tr>
        <th>Tên sản phẩm</th>
        <th>Số lượng</th>
        <th>Giá tiền</th>
        <th>Trạng thái</th>
        <th>Yêu cầu trả hàng</th>
      </tr>";


        while ($it = $items->fetch_assoc()) {

        $attrs = json_decode($it['variant'], true);
        $img = $it['image_url'] ?: '/techzone/assets/images/no-image.png';
        ?>

        <tr>
            <td>
            <div class="d-flex align-items-center gap-3">
                <img src="<?= htmlspecialchars($img) ?>"
                     style="width:60px;height:60px;object-fit:cover;border-radius:8px">

                <div>
                    <div class="fw-semibold">
                        <?= htmlspecialchars($it['product_name']) ?>
                    </div>

                    <div class="text-muted small">
                        <?php if (!empty($attrs['ram'])): ?>
                            RAM: <?= $attrs['ram'] ?> ·
                        <?php endif; ?>
                        <?php if (!empty($attrs['ssd'])): ?>
                            SSD: <?= $attrs['ssd'] ?> ·
                        <?php endif; ?>
                        <?php if (!empty($attrs['color'])): ?>
                            Màu: <?= $attrs['color'] ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </td>

        <td><?= $it['quantity'] ?></td>
        <td><?= number_format($it['price']) ?>₫</td>
        <td><?= htmlspecialchars($order['status']) ?></td>
        <td>
    <?php
    // kiểm tra đã gửi yêu cầu trả chưa
    $chk = $conn->prepare("
        SELECT id 
        FROM order_item_returns 
        WHERE order_item_id = ?
        LIMIT 1
    ");
    $chk->bind_param("i", $it['id']);
    $chk->execute();
    $returned = $chk->get_result()->num_rows > 0;
    ?>

    <?php if ($returned): ?>
        <span class="badge bg-secondary">Đã yêu cầu</span>
    <?php else: ?>
        <a href="account.php?tab=return_item&order_item_id=<?= $it['id'] ?>&order_id=<?= $order_id ?>"
           class="btn btn-sm btn-warning">
            Trả hàng
        </a>
    <?php endif; ?>
</td>

    </tr>

    <?php
}


echo "</table>";


} elseif ($tab === 'return_item') {

    $order_item_id = intval($_GET['order_item_id']);
    $order_id = intval($_GET['order_id']);
    $uid = $_SESSION['user_id'];

    // Kiểm tra đơn hàng có thuộc user không
    $check = $conn->prepare("
    SELECT 
        oi.*, 
        s.sku_code, 
        s.variant,
        p.name,
        o.user_id
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.id
    JOIN sku s ON oi.sku_id = s.id
    JOIN spu p ON s.spu_id = p.id
    WHERE oi.id = ?
    LIMIT 1
");

if (!$check) {
    die("SQL ERROR: " . $conn->error);
}

$check->bind_param("i", $order_item_id);
$check->execute();
$item = $check->get_result()->fetch_assoc();

    if (!$item || $item['user_id'] != $uid) {
        echo "<p>Dữ liệu không hợp lệ.</p>";
        return;
    }

    echo "<h3 class='fw-bold mb-3'>Trả hàng sản phẩm</h3>";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $qty = intval($_POST['quantity']);
        $reason = trim($_POST['reason']);

        if ($qty < 1 || $qty > $item['quantity']) {
            echo "<p class='text-danger'>Số lượng không hợp lệ.</p>";
        } else {

            $stmt = $conn->prepare("
                INSERT INTO order_item_returns (order_id, order_item_id, sku_id, quantity, reason, status)
                VALUES (?, ?, ?, ?, ?, 'pending')
            ");
            $stmt->bind_param(
                "iiiis",
                $order_id,
                $order_item_id,
                $item['sku_id'],
                $qty,
                $reason
            );
            $stmt->execute();

            echo "<p class='text-success'>Gửi yêu cầu trả hàng thành công.</p>";
            echo "<a href='account.php?tab=order_detail&id=$order_id' class='btn btn-primary'>Quay lại đơn hàng</a>";
            return;
        }
    }

    echo "
        <p><strong>Sản phẩm:</strong> {$item['name']}</p>
        <p><strong>Số lượng mua:</strong> {$item['quantity']}</p>

        <form method='post'>
            <label class='form-label'>Số lượng muốn trả:</label>
            <input type='number' name='quantity' class='form-control' min='1' max='{$item['quantity']}' required>

            <label class='form-label mt-3'>Lý do trả:</label>
            <textarea name='reason' class='form-control' required></textarea>

            <button type='submit' class='btn btn-danger mt-3'>Gửi yêu cầu trả hàng</button>
        </form>
    ";

} elseif ($tab === 'history') {

    echo "<h3 class='fw-bold mb-4 text-primary-custom'>Sản phẩm đã xem</h3>";

    $stmt = $conn->prepare("
        SELECT 
            vh.*,
            p.name,
            s.id AS sku_id,
            s.price,
            s.promo_price,
            si.image_url
        FROM view_history vh
        JOIN spu p ON vh.spu_id = p.id
        JOIN sku s 
            ON s.spu_id = p.id
            AND s.id = (
                SELECT id 
                FROM sku 
                WHERE spu_id = p.id 
                ORDER BY id ASC 
                LIMIT 1
            )
        LEFT JOIN sku_images si 
            ON si.sku_id = s.id AND si.is_primary = 1
        WHERE vh.user_id = ?
        ORDER BY vh.viewed_at DESC
        LIMIT 20
    ");

    if (!$stmt) {
        die("SQL ERROR HISTORY: " . $conn->error);
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 0) {
        echo "<p>Bạn chưa xem sản phẩm nào.</p>";
    } else {

        echo "<div class='row'>";

        while ($row = $res->fetch_assoc()) {

            $img = $row['image_url'] ?: "/techzone/assets/images/no-image.png";
            $price = $row['promo_price'] ?: $row['price'];

            echo "
<div class='col-md-3 mb-4'>
    <div class='card view-card'>
        <img src='$img' class='card-img-top view-card-img'>
        <div class='card-body view-card-body'>
            <h6 class='card-title'>" . htmlspecialchars($row['name']) . "</h6>
            <p class='text-danger fw-bold'>" . number_format($price) . " đ</p>
            <a href='product.php?spu_id={$row['spu_id']}' 
   class='btn-outline-primary-custom'>
   Xem lại
</a>

        </div>
    </div>
</div>";

        }

        echo "</div>";
    }
}



    ?>
    </div>

</div>
</div>
<?php include 'partials/footer.php'; ?>


</body>
</html>

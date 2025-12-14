<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

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
    <style>
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #fff;
            border-right: 1px solid #ddd;
            padding: 20px;
        }
        .sidebar a {
            display:block;
            padding:10px 0;
            color:#333;
            text-decoration:none;
            font-size:16px;
            border-bottom:1px solid #eee;
        }
        .sidebar a:hover { color:#0d6efd; }
.view-card {
    height: 100%;                 /* Card luôn full chiều cao của cột */
    display: flex;
    flex-direction: column;
}

.view-card-img {
    height: 180px;                /* Cố định chiều cao ảnh */
    object-fit: contain;          /* Không méo ảnh */
    background-color: #f8f8f8;    /* Làm nền sáng cho ảnh */
    padding: 10px;
}

.view-card-body {
    flex-grow: 1;                 /* Body căng đều, giúp nút luôn nằm dưới */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
</style>



</head>
<body class="bg-light">

<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h5 class="fw-bold mb-3"><?= $user['fullname'] ?></h5>

        <a href="?tab=profile">Thông tin tài khoản</a>
        <a href="?tab=address">Địa chỉ</a>
        <a href="?tab=orders">Quản lý đơn hàng</a>
        <a href="?tab=history">Sản phẩm đã xem</a>
        <a href="logout.php" class="text-danger fw-bold">Đăng xuất</a>
    </div>

    <!-- CONTENT -->
    <div class="flex-fill p-4">
    <?php
        $tab = $_GET['tab'] ?? 'profile';

        if ($tab === 'profile') {

            if (isset($_GET['updated'])) {
                echo "<div class='alert alert-success'>Cập nhật thông tin thành công.</div>";
            }

            echo "<h3 class='fw-bold mb-4'>Thông tin tài khoản</h3>";
    ?>

            <form method="POST">
                <input type="hidden" name="update_profile" value="1">

                <div class="mb-3">
                    <label class="form-label">Họ tên</label>
                    <input type="text" name="fullname" class="form-control" 
                           value="<?= $user['fullname'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Giới tính</label>
                    <select name="gender" class="form-control">
                        <option value="">-- Chọn giới tính --</option>
                        <option value="Nam"   <?= $user['gender']=='Nam'?'selected':'' ?>>Nam</option>
                        <option value="Nữ"    <?= $user['gender']=='Nữ'?'selected':'' ?>>Nữ</option>
                        <option value="Khác"  <?= $user['gender']=='Khác'?'selected':'' ?>>Khác</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" 
                           value="<?= $user['phone'] ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" 
                           value="<?= $user['email'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="birthday" class="form-control" 
                           value="<?= $user['birthday'] ?>">
                </div>

                <button class="btn btn-primary">Lưu thông tin</button>
            </form>

    <?php
        } elseif ($tab === 'address') {

            // LẤY ĐỊA CHỈ HIỆN TẠI TỪ DB
    $currentProvince = $user['province_id'];
    $currentDistrict = $user['district_id'];
    $currentStreet   = $user['street'];
?>
    <h3 class="fw-bold mb-3">Địa chỉ</h3>

    <div class="row g-3">

        <div class="col-md-4">
            <label class="form-label">Tỉnh / Thành phố</label>
            <select id="province" class="form-select">
                <option value="">--Chọn tỉnh/thành phố--</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Quận / Huyện</label>
            <select id="district" class="form-select">
                <option value="">--Chọn quận/huyện--</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Tên đường</label>
            <input type="text" id="street" class="form-control"
                value="<?= htmlspecialchars($currentStreet) ?>"
                placeholder="VD: 123 Lê Lợi">
        </div>

    </div>

    <button id="saveAddress" class="btn btn-primary mt-3">Lưu địa chỉ</button>

    <hr class="my-4">

    <h5>Địa chỉ hiện tại:</h5>
    <p>
        <b>Tỉnh:</b> <span id="textProvince"></span><br>
        <b>Quận/Huyện:</b> <span id="textDistrict"></span><br>
        <b>Đường:</b> <?= htmlspecialchars($currentStreet ?? "") ?>
    </p>

    <script>
        let currentProvince = "<?= $currentProvince ?>";
        let currentDistrict = "<?= $currentDistrict ?>";

        // LOAD PROVINCES
        fetch("get_provinces.php")
            .then(res => res.json())
            .then(data => {
                let province = document.getElementById("province");
                let textProvince = document.getElementById("textProvince");

                data.forEach(p => {
                    let opt = document.createElement("option");
                    opt.value = p.id;
                    opt.textContent = p.name;

                    if (p.id == currentProvince) {
                        opt.selected = true;
                        textProvince.textContent = p.name;
                    }

                    province.appendChild(opt);
                });

                if (currentProvince) loadDistricts(currentProvince, true);
            });

        // HÀM LOAD DISTRICTS
        function loadDistricts(pid, isFirstLoad = false) {
            fetch("get_districts.php?province_id=" + pid)
                .then(res => res.text())
                .then(html => {
                    let district = document.getElementById("district");
                    let textDistrict = document.getElementById("textDistrict");

                    district.innerHTML = html;

                    if (isFirstLoad && currentDistrict) {
                        district.value = currentDistrict;

                        // SET TEXT
                        let selected = district.options[district.selectedIndex];
                        if (selected) textDistrict.textContent = selected.text;
                    }
                });
        }

        // Khi chọn tỉnh -> load district
        document.getElementById("province").addEventListener("change", function () {
            loadDistricts(this.value, false);
        });

        // LƯU VÀO DB
        document.getElementById("saveAddress").addEventListener("click", function () {
            let province = document.getElementById("province").value;
            let district = document.getElementById("district").value;
            let street   = document.getElementById("street").value;

            let form = new FormData();
            form.append("province", province);
            form.append("district", district);
            form.append("street", street);

            fetch("save_address.php", {
                method: "POST",
                body: form
            })
            .then(res => res.json())
            .then(data => {
                alert(data.msg);
                location.reload();
            });
        });
    </script>

<?php
} elseif ($tab === 'orders') {

    echo "<h3 class='fw-bold mb-3'>Quản lý đơn hàng</h3>";

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
                        <a href='account.php?tab=order_detail&id={$o['id']}' class='btn btn-sm btn-primary'>
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

    echo "<p><strong>Trạng thái:</strong> {$order['status']}</p>";
    echo "<p><strong>Tổng tiền:</strong> " . number_format($order['total']) . "₫</p>";
    echo "<p><strong>Ngày tạo:</strong> {$order['created_at']}</p>";

    // Lấy danh sách sản phẩm
    $stmt = $conn->prepare("
    SELECT oi.*, s.sku_code, s.variant
    FROM order_items oi
    JOIN sku s ON oi.sku_id = s.id
    WHERE oi.order_id=?
");

    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $items = $stmt->get_result();

    echo "<h5 class='mt-4'>Sản phẩm trong đơn</h5>";
    echo "<table class='table table-bordered'>";
    echo "<tr>
            <th>Sản phẩm</th>
            <th>SL</th>
            <th>Giá</th>
            <th>Tổng</th>
            <th>Trả hàng</th>
          </tr>";

    while ($it = $items->fetch_assoc()) {

        // Kiểm tra đã yêu cầu trả chưa
        $check = $conn->prepare("SELECT id FROM order_item_returns WHERE order_item_id=?");
        
        $check->bind_param("i", $it['id']);
        $check->execute();
        $returned = $check->get_result()->num_rows > 0;

        echo "<tr>
                {$it['sku_code']} ({$it['variant']})
                <td>{$it['quantity']}</td>
                <td>" . number_format($it['price']) . "₫</td>
                <td>" . number_format($it['price'] * $it['quantity']) . "₫</td>
                <td>";

        if ($returned) {
            echo "<span class='text-danger'>Đã yêu cầu trả</span>";
        } else {
            echo "<a href='account.php?tab=return_item&order_item_id={$it['id']}&order_id={$order_id}' 
                     class='btn btn-sm btn-warning'>
                    Trả hàng
                  </a>";
        }

        echo "</td></tr>";
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

    echo "<h3 class='fw-bold mb-3'>Sản phẩm đã xem</h3>";

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
            <a href='product_detail.php?id={$row['spu_id']}' class='btn btn-primary btn-sm'>Xem lại</a>
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

</body>
</html>

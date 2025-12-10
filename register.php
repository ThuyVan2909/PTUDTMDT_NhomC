<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone    = trim($_POST['phone']);
    $province = intval($_POST['province_id']);
    $district = intval($_POST['district_id']);
    $address  = trim($_POST['address']);

    // Check email tồn tại
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $error = "Email đã tồn tại!";
    } else {
        // Thêm user mới
        $stmt = $conn->prepare("INSERT INTO users (fullname,email,password,phone,address,province_id,district_id) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssiii", $fullname, $email, $password, $phone, $address, $province, $district);
        if ($stmt->execute()) {
            $_SESSION['user_id']   = $stmt->insert_id;
            $_SESSION['user_name'] = $fullname;
            $_SESSION['role']      = 'customer';
            header("Location: index.php");
            exit;
        } else {
            $error = "Đăng ký thất bại: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4">Đăng ký tài khoản</h2>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label>Họ tên</label>
            <input type="text" name="fullname" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Số điện thoại</label>
            <input type="text" name="phone" class="form-control">
        </div>

        <div class="mb-3">
            <label>Tỉnh/Thành</label>
            <select name="province_id" id="province" class="form-control" required>
                <option value="">Chọn Tỉnh/Thành</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Quận/Huyện</label>
            <select name="district_id" id="district" class="form-control" required>
                <option value="">Chọn Quận/Huyện</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Địa chỉ cụ thể</label>
            <input type="text" name="address" class="form-control" placeholder="Số nhà, đường..." required>
        </div>

        <button type="submit" class="btn btn-primary">Đăng ký</button>
    </form>
</div>

<script>
$(document).ready(function(){
    // Load provinces
    $.getJSON('get_provinces.php', function(data){
        var html = '<option value="">Chọn Tỉnh/Thành</option>';
        data.forEach(function(p){
            html += `<option value="${p.id}">${p.name}</option>`;
        });
        $('#province').html(html);
    });

    // Load districts khi chọn province
    $('#province').change(function(){
        var province_id = $(this).val();
        if(province_id){
            $.getJSON('get_districts.php', {province_id: province_id}, function(data){
                var html = '<option value="">Chọn Quận/Huyện</option>';
                data.forEach(function(d){
                    html += `<option value="${d.id}">${d.name}</option>`;
                });
                $('#district').html(html);
            });
        } else {
            $('#district').html('<option value="">Chọn Quận/Huyện</option>');
        }
    });
});
</script>

</body>
</html>

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
    $street = trim($_POST['street']);


    // Check email tồn tại
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $error = "Email đã tồn tại!";
    } else {
        // Thêm user mới
        $stmt = $conn->prepare("INSERT INTO users (fullname,email,password,phone,street,province_id,district_id) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("ssssiii", $fullname, $email, $password, $phone, $street, $province, $district);
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
    <link rel="stylesheet" href="assets/css/register.css">
</head>
    <body class="bg-light">

<div class="container py-5 d-flex justify-content-center">
    <div class="card shadow-lg border-0" style="max-width: 600px; width: 100%; border-radius: 16px;">
        
        <div class="register-header text-white"
     style="background-color:#1A3D64;
            border-radius:16px 16px 0 0;">

    <div class="d-flex align-items-center justify-content-between">
        <div class="text-start">
            <h3 class="mb-1 fw-semibold">Đăng ký tài khoản</h3>  
        </div> 
        <img src="assets/images/LogoRemoveBg.png"
             alt="logo"
             style="height:50px; width:auto;">

        
        
    </div>

</div>

        <div class="card-body p-4">

        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Họ tên</label>
                    <input type="text" name="fullname" class="form-control rounded-3" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control rounded-3" required>
                    <div class="mt-2">
</div>

                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Mật khẩu</label>
                    <input type="password"
                    name="password"
                    id="password"
                    class="form-control rounded-3"
                    required>
                </div>
                <div class="progress" style="height: 6px;">
                    <div id="password-strength-bar"
                        class="progress-bar"
                        style="width: 0%;"></div>
                </div>
                <small id="password-strength-text" class="fw-semibold"></small>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control rounded-3">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Tỉnh / Thành</label>
                        <select name="province_id" id="province" class="form-control rounded-3" required>
                            <option value="">Chọn Tỉnh/Thành</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Quận / Huyện</label>
                        <select name="district_id" id="district" class="form-control rounded-3" required>
                            <option value="">Chọn Quận/Huyện</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
    <label class="form-label fw-semibold">Số nhà, tên đường</label>
    <input type="text"
           name="street"
           class="form-control rounded-3"
           placeholder="Ví dụ: 123 Lê Lợi"
           required>
</div>


                <div class="d-grid">
                    <button type="submit"
                            class="btn text-white fw-semibold rounded-pill py-2"
                            style="background-color:#1A3D64;">
                        Đăng ký
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>


    <script>
$(document).ready(function(){

    // Load provinces (phần này giữ nguyên nếu get_provinces.php trả JSON)
    $.getJSON('get_provinces.php', function(data){
        var html = '<option value="">Chọn Tỉnh/Thành</option>';
        data.forEach(function(p){
            html += `<option value="${p.id}">${p.name}</option>`;
        });
        $('#province').html(html);
    });

    // Load districts theo province (SỬA Ở ĐÂY)
    $('#province').change(function(){
        var province_id = $(this).val();

        if (province_id) {
            $.get('get_districts.php', { province_id: province_id }, function(html){
                $('#district').html(html);
            });
        } else {
            $('#district').html('<option value="">Chọn Quận/Huyện</option>');
        }
    });

});
</script>

<script>
$(document).ready(function () {

    $('#password').on('input', function () {
        const password = $(this).val();
        const bar = $('#password-strength-bar');
        const text = $('#password-strength-text');

        let strength = 0;

        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        bar.removeClass('bg-danger bg-warning bg-success');

        if (password.length === 0) {
            bar.css('width', '0%');
            text.text('');
            return;
        }

        if (strength <= 1) {
            bar.addClass('bg-danger').css('width', '33%');
            text.text('Yếu').css('color', '#dc3545');
        } 
        else if (strength <= 3) {
            bar.addClass('bg-warning').css('width', '66%');
            text.text('Trung bình').css('color', '#ffc107');
        } 
        else {
            bar.addClass('bg-success').css('width', '100%');
            text.text('Mạnh').css('color', '#28a745');
        }
    });

});
</script>




</body>
</html>

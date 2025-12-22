<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Kiểm tra email tồn tại
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $error = "Email không tồn tại!";
    } else {
        // Cập nhật mật khẩu mới
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
        $stmt->bind_param("ss", $new_password, $email);
        if ($stmt->execute()) {
            $success = "Cập nhật mật khẩu thành công. <a href='login.php'>Đăng nhập ngay</a>";
        } else {
            $error = "Lỗi cập nhật mật khẩu: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt lại mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/register.css">
</head>
<body class="bg-light">

<div class="container py-5 d-flex justify-content-center">
    <div class="card shadow-lg border-0" style="max-width: 500px; width:100%; border-radius:16px;">
        <div class="register-header text-white"
             style="background-color:#1A3D64; border-radius:16px 16px 0 0;">
            <h3 class="p-3 mb-0 fw-semibold">Đặt lại mật khẩu</h3>
        </div>
        <div class="card-body p-4">

            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <?php if(isset($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control rounded-3" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Mật khẩu mới</label>
                    <input type="password" name="password" class="form-control rounded-3" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn text-white fw-semibold rounded-pill py-2" style="background-color:#1A3D64;">
                        Cập nhật mật khẩu
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

</body>
</html>

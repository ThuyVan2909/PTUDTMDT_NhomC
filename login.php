<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, fullname, email, password, role FROM users WHERE email = ?");
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();

    if ($user && password_verify($pass, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['fullname'];
        $_SESSION['role']      = $user['role'];

        if ($user['role'] === 'admin') {
        header("Location: admin/admin.php");
    } else {
        header("Location: index.php");
    }
    exit;
} 
else {
    $error = "Sai email hoặc mật khẩu!";
}
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS riêng -->
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>

<div class="login-page">
    <div class="card login-card">

        <!-- HEADER -->
        <div class="login-header">
    <div class="login-header-content">

        <div class="login-top">
            <h3 class="login-title">Đăng nhập</h3>
        </div>

    </div>
</div>


        <!-- FORM -->
        <div class="card-body p-4">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-login">
                        Đăng nhập
                    </button>
                </div>
            </form>

            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        </div>

    </div>
</div>

</body>
</html>


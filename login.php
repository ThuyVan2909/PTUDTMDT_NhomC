<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, fullname, email, password, role FROM users WHERE email = ?");
    if (!$stmt) {
        die("Lỗi chuẩn bị truy vấn: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();

    if ($user) {

        if (password_verify($pass, $user['password'])) {

            session_regenerate_id(true);

            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['fullname'];
            $_SESSION['role']      = $user['role'];

            // Chuyển trang tùy role
            if ($user['role'] === 'admin') {
                header("Location: admin\admin.php");
            } else {
                header("Location: index.php");
            }
            exit;

        } else {
            $error = "Sai mật khẩu!";
        }

    } else {
        $error = "Email không tồn tại!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng nhập</title>
</head>
<body>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" placeholder="Mật khẩu" required />
    <button type="submit">Đăng nhập</button>
</form>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

</body>
</html>

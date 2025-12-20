<?php include 'partials/announcement-bar.php'; ?>
<?php include 'partials/header.php'; ?>
<?php
// TRANG LIÊN HỆ
session_start();
include 'db.php'; // Nếu cần lưu contact form vào DB, nhưng ở đây giả sử xử lý form qua PHP/JS

// Build breadcrumb
$breadcrumbs = [
    ["label" => "Trang chủ", "url" => "index.php"],
    ["label" => "Liên hệ"], // Không có URL vì là trang hiện tại
];
include "breadcrumb.php";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Liên hệ</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/header.css">
<link rel="stylesheet" href="assets/css/footer.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
body { font-family: Arial; background: #f7f7f7; margin: 0; }
.container { width: 1200px; margin: auto; padding: 20px; display: flex; gap: 40px; }
/* LEFT — INFO & MAP */
.left { width: 40%; background: white; padding: 20px; border-radius: 10px; }
.left h2 { margin-top: 0; }
.map { width: 100%; height: 300px; border: 0; border-radius: 8px; }
/* RIGHT — FORM */
.right { width: 60%; background: white; padding: 20px; border-radius: 10px; }
.right h1 { margin-top: 0; }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
.form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; }
.submit-btn {
    width: 100%;
    padding: 18px;
    background: #e30019;
    color: white;
    text-align: center;
    font-size: 20px;
    border-radius: 8px;
    cursor: pointer;
    border: none;
    margin-top: 20px;
}
</style>
</head>
<body>
<div class="container">
    <!-- LEFT COLUMN: CONTACT INFO & MAP -->
    <div class="left">
        <h2>Thông tin liên hệ</h2>
        <p><strong>Địa chỉ:</strong> 123 Đường ABC, Quận 1, TP. Hồ Chí Minh</p>
        <p><strong>Điện thoại:</strong> 0123 456 789</p>
        <p><strong>Email:</strong> support@techzone.com</p>
        <p><strong>Giờ làm việc:</strong> Thứ 2 - Thứ 7: 8h - 18h</p>
        <!-- EMBED GOOGLE MAP (thay src bằng map thực tế) -->
        <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.447!2d106.698!3d10.776!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTDCsDAwJzAwLjAiTiAxMDbCsDAwJzAwLjAiRQ!5e0!3m2!1sen!2s!4v1630000000000" allowfullscreen="" loading="lazy"></iframe>
    </div>
    <!-- RIGHT COLUMN: CONTACT FORM -->
    <div class="right">
        <h1>Gửi liên hệ</h1>
        <form id="contactForm">
            <div class="form-group">
                <label for="name">Họ và tên</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="tel" id="phone" name="phone">
            </div>
            <div class="form-group">
                <label for="message">Tin nhắn</label>
                <textarea id="message" name="message" rows="6" required></textarea>
            </div>
            <button type="submit" class="submit-btn">GỬI LIÊN HỆ</button>
        </form>
    </div>
</div>
<script>
// Xử lý submit form (AJAX để gửi đến server, ví dụ send_contact.php)
document.getElementById("contactForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch("send_contact.php", {
        method: "POST",
        body: formData
    })
    .then(r => r.text())
    .then(msg => {
        alert(msg); // Hoặc hiển thị thông báo thành công
        this.reset(); // Reset form
    })
    .catch(err => alert("Lỗi: " + err));
});
</script>
<?php include 'partials/footer.php'; ?>
</body>
</html>
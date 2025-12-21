<?php include 'partials/announcement-bar.php'; ?>
<?php include 'partials/header.php'; ?>
<?php
// TRANG BLOG DETAIL KHI CLICK VAO 1 BAI VIET
include 'db.php';
$blog_id = isset($_GET['blog_id']) ? intval($_GET['blog_id']) : 0;
if (!$blog_id) { echo "Blog post not found"; exit; }
$blog = $conn->query("SELECT * FROM blogs WHERE id = $blog_id LIMIT 1")->fetch_assoc();
if (!$blog) { echo "Bài viết không tồn tại"; exit; }
// ===============================
// GHI LỊCH SỬ BÀI VIẾT ĐÃ XEM (TÙY CHỌN)
// ===============================
if (isset($_SESSION['user_id'])) {
    $user_id = intval($_SESSION['user_id']);
    // Xóa bản ghi cũ nếu user đã xem bài viết này
    $conn->query("DELETE FROM view_history_blog WHERE user_id = $user_id AND blog_id = $blog_id");
    // Thêm bản ghi mới
    $stmt = $conn->prepare("INSERT INTO view_history_blog (user_id, blog_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $blog_id);
    $stmt->execute();
}
// 1. Lấy data bài viết
$blog_id = $_GET['blog_id'];
$stmt = $conn->prepare("
    SELECT b.title AS blog_title,
           c.id AS category_id,
           c.name AS category_name,
           c.parent_id,
           pc.name AS parent_name
    FROM blogs b
    LEFT JOIN categories c ON b.category_id = c.id
    LEFT JOIN categories pc ON c.parent_id = pc.id
    WHERE b.id = ?
");
$stmt->bind_param("i", $blog_id);
$stmt->execute();
$res = $stmt->get_result();
$data = $res->fetch_assoc();
$blog_title = $data['blog_title'];
$category_id = $data['category_id'];
$category_name = $data['category_name'];
$parent_name = $data['parent_name'];
$parent_id = $data['parent_id'];
// 2. Build breadcrumb
$breadcrumbs = [
    ["label" => "Trang chủ", "url" => "index.php"],
];
// Xác định section dựa theo parent_id (giả sử tương tự, ví dụ blog section)
$section = "blog"; // Hoặc dựa trên parent_id nếu có
// 3. Nếu có danh mục cha
if (!empty($parent_id)) {
    $breadcrumbs[] = [
        "label" => $parent_name,
        "url" => "blog.php?cat=$parent_id"
    ];
}
// 4. Danh mục con
$breadcrumbs[] = [
    "label" => $category_name,
    "url" => "blog.php?cat=$category_id"
];
// 5. Tiêu đề bài viết (không có URL)
$breadcrumbs[] = ["label" => $blog_title];
include "breadcrumb.php";
// Lấy hình ảnh chính (giả sử từ DB, hoặc default)
$main_image = $blog['image_url'] ?? '/techzone/assets/images/no-image.png';
// Lấy nội dung bài viết
$blog_content = $blog['content'] ?? 'Nội dung bài viết ở đây.';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title><?= $blog['title'] ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/header.css">
<link rel="stylesheet" href="assets/css/footer.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
body { font-family: Arial; background: #f7f7f7; margin: 0; }
.container { width: 1200px; margin: auto; padding: 20px; display: flex; gap: 40px; }
/* LEFT — IMAGE */
.images { width: 40%; }
.images img { width: 100%; border-radius: 8px; }
/* RIGHT — INFO */
.info { width: 60%; background: white; padding: 20px; border-radius: 10px; }
.info h1 { margin-top: 0; }
/* CONTENT */
.content { font-size: 16px; line-height: 1.6; color: #333; }
/* BUTTONS (nếu cần, ví dụ share buttons) */
.share-btn {
    padding: 10px 20px;
    border: none;
    margin: 5px;
    background: #e30019;
    color: white;
    border-radius: 6px;
    cursor: pointer;
}
/* COMMENTS SECTION (nếu có) */
.comments { margin-top: 40px; }
</style>
</head>
<body>
<div class="container">
    <!-- LEFT COLUMN: FEATURED IMAGE -->
    <div class="images">
        <img src="<?= htmlspecialchars($main_image) ?>" alt="featured image">
    </div>
    <!-- RIGHT COLUMN: BLOG INFO -->
    <div class="info">
        <h1><?= $blog['title'] ?></h1>
        <!-- AUTHOR & DATE (giả sử từ DB) -->
        <p>Ngày đăng: <?= $blog['created_at'] ?? '21/12/2025' ?> | Tác giả: <?= $blog['author'] ?? 'Admin' ?></p>
        <!-- CONTENT -->
        <div class="content">
            <?= $blog_content ?>
        </div>
        <!-- SHARE BUTTONS (tùy chọn) -->
        <div style="margin-top: 20px;">
            <button class="share-btn">Chia sẻ trên Facebook</button>
            <button class="share-btn" style="background: #ff9900;">Chia sẻ trên X</button>
        </div>
        <!-- COMMENTS (nếu có, fetch động) -->
        <div class="comments">
            <h3>Bình luận</h3>
            <!-- Có thể add form và list comments ở đây -->
            <p>Chưa có bình luận.</p>
        </div>
    </div>
</div>
<?php include 'partials/footer.php'; ?>
</body>
</html>
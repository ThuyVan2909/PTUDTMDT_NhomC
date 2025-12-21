<?php
include "../db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $image_url = $_POST['image_url'];
    $link = $_POST['link'];
    $position = $_POST['position'];
    $sort_order = intval($_POST['sort_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $stmt = $conn->prepare("
        INSERT INTO banners (title, image_url, link, position, sort_order, is_active)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("ssssii",
        $title, $image_url, $link, $position, $sort_order, $is_active
    );
    $stmt->execute();

    header("Location: banners.php");
    exit;
}
?>

<h2>Thêm Banner</h2>

<form method="post">
    <label>Tiêu đề</label><br>
    <input name="title" required><br><br>

    <label>Image URL</label><br>
    <input name="image_url" placeholder="assets/banners/xxx.png" required><br><br>

    <label>Link</label><br>
    <input name="link" required><br><br>

    <label>Vị trí</label><br>
    <select name="position">
        <option value="left">LEFT</option>
        <option value="top">TOP</option>
    </select><br><br>

    <label>Thứ tự</label><br>
    <input type="number" name="sort_order" value="1"><br><br>

    <label>
        <input type="checkbox" name="is_active" checked> Hiển thị
    </label><br><br>

    <button type="submit">Lưu</button>
</form>

<?php
include "../db.php";

$id = intval($_GET['id']);
$banner = $conn->query("SELECT * FROM banners WHERE id = $id")->fetch_assoc();

if (!$banner) {
    die("Banner không tồn tại");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $image_url = $_POST['image_url'];
    $link = $_POST['link'];
    $position = $_POST['position'];
    $sort_order = intval($_POST['sort_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    $stmt = $conn->prepare("
        UPDATE banners
        SET title=?, image_url=?, link=?, position=?, sort_order=?, is_active=?
        WHERE id=?
    ");
    $stmt->bind_param("ssssiii",
        $title, $image_url, $link, $position, $sort_order, $is_active, $id
    );
    $stmt->execute();

    header("Location: banners.php");
    exit;
}
?>

<h2>Sửa Banner</h2>

<form method="post">
    <label>Tiêu đề</label><br>
    <input name="title" value="<?= htmlspecialchars($banner['title']) ?>"><br><br>

    <label>Image URL</label><br>
    <input name="image_url" value="<?= $banner['image_url'] ?>"><br><br>

    <label>Link</label><br>
    <input name="link" value="<?= $banner['link'] ?>"><br><br>

    <label>Vị trí</label><br>
    <select name="position">
        <option value="left" <?= $banner['position']=='left'?'selected':'' ?>>LEFT</option>
        <option value="top" <?= $banner['position']=='top'?'selected':'' ?>>TOP</option>
    </select><br><br>

    <label>Thứ tự</label><br>
    <input type="number" name="sort_order" value="<?= $banner['sort_order'] ?>"><br><br>

    <label>
        <input type="checkbox" name="is_active" <?= $banner['is_active']?'checked':'' ?>>
        Hiển thị
    </label><br><br>

    <button type="submit">Cập nhật</button>
</form>

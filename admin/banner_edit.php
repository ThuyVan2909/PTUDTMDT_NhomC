<?php
include "../db.php";

$id = intval($_GET['id']);
$banner = $conn->query("SELECT * FROM banners WHERE id = $id")->fetch_assoc();
if (!$banner) die("Banner không tồn tại");

// Lấy danh sách SPU
$spus = $conn->query("SELECT id, name FROM spu ORDER BY name ASC");

// Xử lý POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $position = $_POST['position'];
    $sort_order = intval($_POST['sort_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Upload ảnh mới nếu có
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = "../assets/banners/";
        $fileName = time() . "_" . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
        $image_url = "assets/banners/" . $fileName;
    } else {
        $image_url = $banner['image_url'];
    }

    // Link theo SPU
    $spu_id = intval($_POST['spu_id'] ?? 0);
    $link = $spu_id ? "product.php?spu_id=$spu_id" : "";

    $stmt = $conn->prepare("
        UPDATE banners
        SET title=?, image_url=?, link=?, position=?, sort_order=?, is_active=?
        WHERE id=?
    ");
    $stmt->bind_param("ssssiii", $title, $image_url, $link, $position, $sort_order, $is_active, $id);
    $stmt->execute();

    header("Location: admin.php?view=banners");
    exit;
}
?>

<h2>Sửa Banner</h2>

<form method="post" enctype="multipart/form-data">
    <label>Tiêu đề</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($banner['title']) ?>" required><br><br>

    <label>Ảnh banner</label><br>
    <input type="file" name="image" accept="image/*"><br>
    <img id="preview" src="../<?= $banner['image_url'] ?>" style="height:80px;margin-top:10px"><br><br>

    <label>Chọn sản phẩm (SPU)</label><br>
    <select name="spu_id">
        <option value="">-- Không gắn SPU --</option>
        <?php while($s = $spus->fetch_assoc()): ?>
            <option value="<?= $s['id'] ?>" <?= strpos($banner['link'], "spu_id={$s['id']}") !== false ? 'selected' : '' ?>>
                <?= htmlspecialchars($s['name']) ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Vị trí</label><br>
    <select name="position">
        <option value="left" <?= $banner['position']=='left'?'selected':'' ?>>LEFT</option>
        <option value="top" <?= $banner['position']=='top'?'selected':'' ?>>TOP</option>
    </select><br><br>

    <label>Thứ tự</label><br>
    <input type="number" name="sort_order" value="<?= $banner['sort_order'] ?>" required><br><br>

    <label>
        <input type="checkbox" name="is_active" <?= $banner['is_active']?'checked':'' ?>>
        Hiển thị
    </label><br><br>

    <button type="submit">Cập nhật</button>
</form>

<script>
const input = document.querySelector("input[type=file]");
const preview = document.getElementById("preview");

if(input && preview){
    input.addEventListener("change", () => {
        const file = input.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = "block";
        }
    });
}
</script>

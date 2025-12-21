<?php
include "../db.php";

/* Lấy danh sách SPU để chọn */
$spus = $conn->query("
    SELECT id, name, brand
    FROM spu
    ORDER BY brand ASC, name ASC
");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title     = $_POST['title'];
    $spu_id    = (int)$_POST['spu_id'];
    $position  = $_POST['position'];
    $sort      = (int)$_POST['sort_order'];

    // Tạo link tự động từ SPU
    $link = $spu_id > 0 ? "product.php?spu_id=" . $spu_id : "";

    // Upload ảnh
    $uploadDir = "../assets/banners/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['image']['name']);
    $targetPath = $uploadDir . $fileName;

    move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
    $imagePath = "assets/banners/" . $fileName;

    $stmt = $conn->prepare("
        INSERT INTO banners (title, image_url, link, position, sort_order, is_active)
        VALUES (?, ?, ?, ?, ?, 1)
    ");
    $stmt->bind_param("ssssi", $title, $imagePath, $link, $position, $sort);
    $stmt->execute();

    exit;
}
?>

<h2>Thêm Banner</h2>

<div class="card" style="max-width:600px; padding:20px; border-radius:10px; box-shadow:0 0 12px rgba(0,0,0,0.1);">
<form id="addBannerForm" method="post" enctype="multipart/form-data" style="display:flex; flex-direction:column; gap:12px;">


    <label>Tiêu đề banner</label>
    <input type="text" name="title" required style="padding:6px 10px; border-radius:5px; border:1px solid #ccc;">

    <label>Chọn sản phẩm</label>
    <select name="spu_id" required style="padding:6px 10px; border-radius:5px; border:1px solid #ccc;">
        <option value="">-- Chọn sản phẩm --</option>
        <?php while ($s = $spus->fetch_assoc()): ?>
            <option value="<?= $s['id'] ?>">
                <?= htmlspecialchars($s['brand'] . " - " . $s['name']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Ảnh banner</label>
    <input type="file" name="image" accept="image/*" required style="padding:6px 10px;">

    <img id="preview" style="
        margin-top:10px;
        max-width:100%;
        height:auto;
        display:none;
        border:1px solid #ccc;
        border-radius:6px;
        padding:4px;
    ">

    <label>Vị trí hiển thị</label>
    <select name="position" style="padding:6px 10px; border-radius:5px; border:1px solid #ccc;">
        <option value="left">LEFT (Banner dọc)</option>
        <option value="top">TOP (Banner slide)</option>
    </select>

    <label>Thứ tự hiển thị</label>
    <input type="number" name="sort_order" value="1" min="1" style="padding:6px 10px; border-radius:5px; border:1px solid #ccc;">

    <div style="display:flex; gap:10px; margin-top:12px;">
        <button type="submit" style="padding:8px 14px; background:#135071; color:#fff; border:none; border-radius:6px; cursor:pointer;">Lưu banner</button>
        <a href="admin.php?view=banners" style="padding:8px 14px; background:#ccc; color:#333; border-radius:6px; text-decoration:none; text-align:center;">Hủy</a>
    </div>

</form>
</div>

<script>
/* Preview ảnh upload */
const input = document.querySelector("input[type=file]");
const preview = document.getElementById("preview");

input.addEventListener("change", () => {
    const file = input.files[0];
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = "block";
    }
});
</script>

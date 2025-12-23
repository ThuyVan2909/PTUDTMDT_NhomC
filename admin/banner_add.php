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
<div class="custom-modal-content">
    <div class="modal-header-dark">
        THÊM BANNER MỚI
    </div>

    <form id="addBannerForm" method="post" enctype="multipart/form-data" class="modal-padding">
        <div class="form-group-custom">
            <label>Tiêu đề banner</label>
            <input type="text" name="title" required>
        </div>

        <div class="form-group-custom">
            <label>Chọn sản phẩm</label>
            <select name="spu_id" required>
                <option value="">-- Chọn sản phẩm --</option>
                <?php while ($s = $spus->fetch_assoc()): ?>
                    <option value="<?= $s['id'] ?>">
                        <?= htmlspecialchars($s['brand'] . " - " . $s['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group-row">
            <div class="form-group-custom">
                <label>Vị trí</label>
                <select name="position">
                    <option value="top">TOP (Slide)</option>
                    <option value="left">LEFT (Dọc)</option>
                </select>
            </div>
            <div class="form-group-custom">
                <label>Thứ tự</label>
                <input type="number" name="sort_order" value="1" min="1">
            </div>
        </div>

        <div class="form-group-custom">
            <label>Ảnh banner</label>
            <input type="file" name="image" accept="image/*" required id="bannerFile">
            <div class="preview-container">
                <img id="preview" src="" style="display:none;">
            </div>
        </div>

        <div class="modal-actions">
            <button type="submit" class="btn-save-dark">Lưu</button>
            <button type="button" class="btn-cancel-light" onclick="closeBannerModal()">Hủy</button>
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

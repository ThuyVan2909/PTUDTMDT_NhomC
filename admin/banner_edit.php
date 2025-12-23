<?php
include "../db.php";

$id = intval($_GET['id']);
$banner = $conn->query("SELECT * FROM banners WHERE id = $id")->fetch_assoc();
if (!$banner) die("Banner không tồn tại");

$spus = $conn->query("SELECT id, name, brand FROM spu ORDER BY brand ASC, name ASC");

// Xử lý POST (GIỮ NGUYÊN LOGIC)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $position = $_POST['position'];
    $sort_order = intval($_POST['sort_order']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if (!empty($_FILES['image']['name'])) {
        $uploadDir = "../assets/banners/";
        $fileName = time() . "_" . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName);
        $image_url = "assets/banners/" . $fileName;
    } else {
        $image_url = $banner['image_url'];
    }

    $spu_id = intval($_POST['spu_id'] ?? 0);
    $link = $spu_id ? "product.php?spu_id=$spu_id" : "";

    $stmt = $conn->prepare("
        UPDATE banners
        SET title=?, image_url=?, link=?, position=?, sort_order=?, is_active=?
        WHERE id=?
    ");
    $stmt->bind_param("ssssiii", $title, $image_url, $link, $position, $sort_order, $is_active, $id);
    $stmt->execute();
    exit;
}
?>

<div class="custom-modal-content">
    <div class="modal-header-dark">
        CHỈNH SỬA BANNER
    </div>

    <form method="post" enctype="multipart/form-data" class="modal-padding">

        <div class="form-group-custom">
            <label>Tiêu đề banner</label>
            <input type="text" name="title"
                   value="<?= htmlspecialchars($banner['title']) ?>" required>
        </div>

        <div class="form-group-custom">
            <label>Chọn sản phẩm</label>
            <select name="spu_id">
                <option value="">-- Không gắn SPU --</option>
                <?php while ($s = $spus->fetch_assoc()): ?>
                    <option value="<?= $s['id'] ?>"
                        <?= strpos($banner['link'], "spu_id={$s['id']}") !== false ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['brand'] . " - " . $s['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group-row">
            <div class="form-group-custom">
                <label>Vị trí</label>
                <select name="position">
                    <option value="top" <?= $banner['position']=='top'?'selected':'' ?>>TOP (Slide)</option>
                    <option value="left" <?= $banner['position']=='left'?'selected':'' ?>>LEFT (Dọc)</option>
                </select>
            </div>

            <div class="form-group-custom">
                <label>Thứ tự</label>
                <input type="number" name="sort_order"
                       value="<?= $banner['sort_order'] ?>" min="1">
            </div>
        </div>

        <div class="form-group-custom">
            <label>Ảnh banner</label>
            <input type="file" name="image" accept="image/*">
            <div class="preview-container">
                <img id="preview"
                     src="../<?= $banner['image_url'] ?>"
                     style="display:block">
            </div>
        </div>

        <div class="form-group-custom">
            <label>
                <input type="checkbox" name="is_active"
                    <?= $banner['is_active'] ? 'checked' : '' ?>>
                Hiển thị banner
            </label>
        </div>

        <div class="modal-actions">
            <button type="submit" class="btn-save-dark">Lưu thay đổi</button>
            <button type="button" class="btn-cancel-light"
                    onclick="closeBannerModal()">Hủy</button>
        </div>
    </form>
</div>

<script>S
const input = document.querySelector("input[type=file]");
const preview = document.getElementById("preview");

if (input) {
    input.addEventListener("change", () => {
        const file = input.files[0];
        if (file) {
            preview.src = URL.createObjectURL(file);
            preview.style.display = "block";
        }
    });
}
</script>
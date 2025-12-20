<?php
include '../db.php';

$spu_id = intval($_GET['id'] ?? 0);
if ($spu_id <= 0) die("Invalid SPU ID");

// ===== GET SPU =====
$stmt = $conn->prepare("SELECT * FROM spu WHERE id = ?");
$stmt->bind_param("i", $spu_id);
$stmt->execute();
$spu = $stmt->get_result()->fetch_assoc();
if (!$spu) die("SPU not found");

// ===== UPDATE SPU =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $category_id = intval($_POST['category_id']);
    $description = $_POST['description'];

    $update = $conn->prepare("
        UPDATE spu
        SET name=?, brand=?, category_id=?, description=?
        WHERE id=?
    ");
    $update->bind_param("ssisi", $name, $brand, $category_id, $description, $spu_id);

    if ($update->execute()) {
        echo "<script>alert('Cập nhật thành công');window.location='product_edit.php?id=$spu_id';</script>";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

// ===== GET SKU LIST =====
$sku_res = $conn->query("
    SELECT 
        s.*,
        (SELECT image_url FROM sku_images WHERE sku_id = s.id AND is_primary = 1 LIMIT 1) AS primary_image
    FROM sku s
    WHERE s.spu_id = $spu_id
    ORDER BY s.id ASC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Product</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { 
    font-family: Arial, sans-serif; 
    background:#f5f6fa; 
    padding:20px;
}

/* CARD WRAPPER */
.card {
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 8px rgba(0,0,0,0.08);
    margin-bottom:25px;
}

/* HEADINGS */
h2 { margin-top:0; }

/* TABLE */
table { 
    width: 100%; 
    border-collapse: collapse; 
    background:white;
}
th {
    background:#135071; 
    color:white; 
    padding:10px; 
    text-align:left;
}
td { 
    padding:10px; 
    border-bottom: 1px solid #eee; 
}
tr:hover { background:#f9f9f9; }

/* FORM */
input, textarea {
    padding:8px;
    border:1px solid #ccc;
    border-radius:6px;
    margin-top:4px;
}

/* BUTTON */
.btn {
    display:inline-block;
    padding:8px 14px;
    background:#135071;
    color:white;
    border-radius:6px;
    text-decoration:none;
}
.btn:hover { background:#0f3e57; }

.btn-small {
    padding:6px 10px;
    font-size:13px;
}

/* LINK BUTTON SMALL */
.link-btn {
    color:#135071;
    font-weight:bold;
    cursor:pointer;
}
.link-btn:hover { text-decoration:underline; }

/* IMAGE */
img {
    border-radius:8px;
}

/* MODAL */
#modal {
    display:none; 
    position:fixed; 
    top:0; left:0; 
    width:100%; height:100%;
    background:rgba(0,0,0,0.6); 
    z-index:9999;
    justify-content:center; 
    align-items:center;
}
#modal-content {
    background:white; 
    width:650px; 
    padding:20px; 
    border-radius:10px;
    max-height:90%; 
    overflow:auto;
}

.card {
    border: none;
}

.table td, .table th {
    vertical-align: middle;
}

pre {
    background: #f8f9fa;
    padding: 6px 8px;
    border-radius: 6px;
    font-size: 13px;
}

.table thead th{
    background:#1A3D64 !important;
    color:#fff !important;
    font-weight:700;
    text-transform: uppercase;
    border:none;
    padding:14px 12px;
    text-align:center;
}
.table {
    border-radius: 12px;
    overflow: hidden;
    border: #0f3e57;
}

.table-bordered > :not(caption) > * > * {
    border-width: 1px;
    border-color: #dee2e6;
}

.spu-card {
    border-left: 6px solid #1A3D64;
}

.spu-header h2 {
    color: #1A3D64;
    font-weight: 700;
}

.spu-header i {
    margin-right: 6px;
}
.form-control:focus {
    border-color: #1A3D64;
    box-shadow: 0 0 0 0.15rem rgba(26, 61, 100, 0.25);
}
</style>

</head>

<body>

<!-- ======================== SPU CARD =========================== -->
<div class="card spu-card">
    <div class="spu-header mb-4">
        <h2 class="mb-0">
            <i class="bi bi-box-seam"></i> Chỉnh sửa SPU sản phẩm
        </h2>
        <small class="text-muted">Thông tin chung của sản phẩm</small>
    </div>

    <form method="post" class="row g-3">

        <div class="col-md-6">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control"
                   value="<?= htmlspecialchars($spu['name']) ?>">
        </div>

        <div class="col-md-6">
            <label class="form-label">Thương hiệu</label>
            <input type="text" name="brand" class="form-control"
                   value="<?= htmlspecialchars($spu['brand']) ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label">Category ID</label>
            <input type="number" name="category_id" class="form-control"
                   value="<?= $spu['category_id'] ?>">
        </div>

        <div class="col-md-12">
            <label class="form-label">Mô tả</label>
            <textarea name="description" rows="4"
                      class="form-control"><?= htmlspecialchars($spu['description']) ?></textarea>
        </div>

        <div class="col-md-12">
            <button class="btn btn-primary px-4">
                <i class="bi bi-save"></i> Lưu SPU
            </button>
        </div>

    </form>
</div>
<!-- ======================== SKU CARD =========================== -->

<div class="card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Danh sách SKU</h2>

        <a href="sku_add.php?spu_id=<?= $spu_id ?>"
           class="btn btn-success btn-sm">
            <i class="bi bi-plus-circle"></i> Thêm SKU
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
    <thead class="table-dark">
        <tr>
            <th>Ảnh</th>
            <th>ID</th>
            <th>SKU Code</th>
            <th>Variant</th>
            <th>Giá</th>
            <th>Giá ưu đãi</th>
            <th>Stock</th>
            <th>Kho</th>
            <th width="140">Hành động</th>
        </tr>
        </thead>

<?php while ($sku = $sku_res->fetch_assoc()) { ?>
    <tr>
        <td><img src="<?= $sku['primary_image'] ?: '/techzone/assets/images/no-image.png' ?>" width="65"></td>
        <td><?= $sku['id'] ?></td>
        <td><?= $sku['sku_code'] ?></td>
        <td><pre style="margin:0"><?= $sku['variant'] ?></pre></td>
        <td><?= number_format($sku['price']) ?></td>
        <td><?= number_format($sku['promo_price']) ?></td>
        <td><?= $sku['stock'] ?></td>
        <td><?= $sku['warehouse_location'] ?></td>
        <td>
            <button class="btn btn-sm btn-outline-primary me-1"
                    onclick="openModal('sku_edit_popup.php?id=<?= $sku['id'] ?>')">
                <i class="bi bi-pencil"></i>
            </button>

            <button class="btn btn-sm btn-outline-secondary"
                    onclick="openModal('sku_images_popup.php?sku_id=<?= $sku['id'] ?>')">
                <i class="bi bi-images"></i>
            </button>
        </td>

    </tr>
<?php } ?>
</table>
</div>

<!-- ======================== MODAL =========================== -->

<div id="modal">
    <div id="modal-content">Loading...</div>
</div>

<script>
function openModal(url) {
    const modal = document.getElementById("modal");
    const content = document.getElementById("modal-content");

    modal.style.display = "flex";
    content.innerHTML = "Loading...";

    fetch(url)
        .then(res => res.text())
        .then(html => content.innerHTML = html)
        .catch(err => content.innerHTML = "Lỗi tải popup: " + err);
}

function closeModal() {
    document.getElementById("modal").style.display = "none";
}
</script>

</body>
</html>

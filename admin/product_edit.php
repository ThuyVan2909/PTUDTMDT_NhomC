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
</style>

</head>

<body>

<!-- ======================== SPU CARD =========================== -->
<div class="card">
<h2>Chỉnh sửa SPU sản phẩm</h2>

<form method="post">

    <label>Tên sản phẩm</label><br>
    <input type="text" name="name" value="<?= $spu['name'] ?>" style="width:350px"><br><br>

    <label>Thương hiệu</label><br>
    <input type="text" name="brand" value="<?= $spu['brand'] ?>" style="width:350px"><br><br>

    <label>Category ID</label><br>
    <input type="number" name="category_id" value="<?= $spu['category_id'] ?>"><br><br>

    <label>Mô tả</label><br>
    <textarea name="description" rows="4" style="width:450px"><?= $spu['description'] ?></textarea><br><br>

    <button class="btn" type="submit">Lưu SPU</button>
</form>
</div>

<!-- ======================== SKU CARD =========================== -->

<div class="card">
<h2>Danh sách SKU</h2>

<a class="btn-small btn" href="sku_add.php?spu_id=<?= $spu_id ?>">+ Thêm SKU</a>

<table>
    <tr>
        <th>Ảnh</th>
        <th>ID</th>
        <th>SKU Code</th>
        <th>Variant</th>
        <th>Giá</th>
        <th>Giá ưu đãi</th>
        <th>Stock</th>
        <th>Kho</th>
        <th>Hành động</th>
    </tr>

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
            <span class="link-btn" onclick="openModal('sku_edit_popup.php?id=<?= $sku['id'] ?>')">Edit</span> |
            <span class="link-btn" onclick="openModal('sku_images_popup.php?sku_id=<?= $sku['id'] ?>')">Images</span>
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

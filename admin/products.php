<?php
include "../db.php";  // đúng đường dẫn

// Tổng số sản phẩm
$totalProducts = $conn->query("SELECT COUNT(*) AS cnt FROM spu")->fetch_assoc()['cnt'] ?? 0;

// Số lượng sản phẩm theo danh mục Laptop / Điện thoại
$prodByCategory = $conn->query("
    SELECT 
        c.id AS parent_id,
        c.name AS category_name,
        COUNT(sp.id) AS total
    FROM categories c
    LEFT JOIN categories sub ON sub.parent_id = c.id OR sub.id = c.id
    LEFT JOIN spu sp ON sp.category_id = sub.id
    WHERE c.parent_id IS NULL  -- chỉ lấy danh mục gốc
    GROUP BY c.id
")->fetch_all(MYSQLI_ASSOC);


// Lấy toàn bộ SPU
$spus = $conn->query("SELECT * FROM spu");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Danh sách sản phẩm</title>
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body { font-family: Arial; padding: 20px; }
.card { background: #fff; padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
table { border-collapse: collapse; width: 100%; margin-top: 20px; }
th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
img { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; }

body {
    background: #f4f6f9;
    font-size: 14px;
}

.dashboard-card {
    background: #1A3D64;
    border-radius: 14px;
    padding: 20px;
    color: #fff;
    box-shadow: 0 8px 20px rgba(0,0,0,.08);
}

.dashboard-card h3 {
    font-size: 28px;
    margin: 0;
}

.dashboard-card small {
    opacity: .85;
}

.bg-main { background: linear-gradient(135deg, #1A3D64, #274f85); }
.bg-sub { background: linear-gradient(135deg, #6c757d, #495057); }

.table thead {
    background: #1A3D64;
    color: #fff;
}

.table tbody tr:hover {
    background: #f1f5f9;
}

.product-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.btn-action {
    padding: 6px 10px;
    font-size: 13px;
}


/* ===== TABLE STYLING ===== */
.table {
    border-radius: 12px;
    overflow: hidden;
    border: none;
}

/* Header */
.table thead th {
    background: linear-gradient(135deg, #1A3D64, #274f85);
    color: #fff;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 13px;
    border: none;
    vertical-align: middle;
}

/* Body */
.table tbody td {
    vertical-align: middle;
    border-color: #e9ecef;
}

/* Zebra rows */
.table tbody tr:nth-child(even) {
    background: #f8fafc;
}

/* Hover row */
.table tbody tr:hover {
    background: #eaf2ff;
    transition: background .2s ease;
}

/* Ảnh */
.product-img {
    background: #fff;
    padding: 4px;
}

/* Cột ID */
.table td:nth-child(2) {
    font-weight: 600;
    color: #1A3D64;
}

/* Action buttons */
.btn-action {
    border-radius: 8px;
}

.btn-outline-primary:hover {
    background: #1A3D64;
    color: #fff;
}

.btn-outline-warning:hover {
    background: #ffc107;
    color: #000;
}

.btn-outline-danger:hover {
    background: #dc3545;
    color: #fff;
}

.bg-techzone {
    background: #1A3D64;
}

</style>
</head>
<body>
<div class="container-fluid py-4">
<h2>Danh sách sản phẩm</h2>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="dashboard-card bg-techzone">
            <small>Tổng sản phẩm</small>
            <h3><?= $totalProducts ?></h3>
        </div>
    </div>

    <?php foreach($prodByCategory as $cat): ?>
    <div class="col-md-4">
        <div class="dashboard-card bg-secondary">
            <small><?= htmlspecialchars($cat['category_name']) ?></small>
            <h3><?= $cat['total'] ?></h3>
        </div>
    </div>
    <?php endforeach; ?>
</div>

 <!-- TABLE -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
    <tr>
        <th width="80">Ảnh</th>
        <th width="60">ID</th>
        <th>Tên sản phẩm</th>
        <th>Mô tả</th>
        <th width="160">Hành động</th>
    </tr>
 </thead>

    <tbody>
<?php while($spu = $spus->fetch_assoc()): 
    $imgRow = $conn->query("
        SELECT image_url 
        FROM sku_images 
        WHERE sku_id = (SELECT id FROM sku WHERE spu_id = {$spu['id']} LIMIT 1)
        LIMIT 1
    ")->fetch_assoc();
    $img = $imgRow['image_url'] ?: '../assets/images/no-image.png';
?>
                        <tr>
                            <td>
                                <img src="<?= $img ?>" class="product-img">
                            </td>
                            <td><?= $spu['id'] ?></td>
                            <td class="fw-semibold">
                                <?= htmlspecialchars($spu['brand'] . " " . $spu['name']) ?>
                            </td>
                            <td class="text-muted">
                                <?= mb_strimwidth(strip_tags($spu['description']), 0, 120, "...") ?>
                            </td>
                            <td>
                                <a href="product_detail.php?id=<?= $spu['id'] ?>" 
                                   class="btn btn-sm btn-outline-primary btn-action">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a href="product_edit.php?id=<?= $spu['id'] ?>" 
                                   class="btn btn-sm btn-outline-warning btn-action">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a href="product_delete.php?id=<?= $spu['id'] ?>" 
                                   class="btn btn-sm btn-outline-danger btn-action"
                                   onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
</table>
</div>
        </div>
    </div>

</div>
</body>
</html>

<?php
include 'db.php';

// nhận dữ liệu filter từ AJAX
$section = $_POST['section'] ?? '';
$category = $_POST['category'] ?? '';
$brand = $_POST['brand'] ?? '';

// base SQL
$sql = "SELECT * FROM spu WHERE 1";

// -----------------------
// 1) Lọc category
// -----------------------
if (!empty($category)) {
    // truyền category trực tiếp
    $sql .= " AND category_id = " . intval($category);
} else {
    // không truyền category → map theo section
    $sectionMap = [
        'phone'  => 1,
        'laptop' => 2,
        'watch'  => 3
    ];

    if (isset($sectionMap[$section])) {
        $parent = intval($sectionMap[$section]);

        // lấy tất cả category thuộc parent
        $sql .= " AND category_id IN (
            SELECT id FROM categories
            WHERE id = $parent OR parent_id = $parent
        )";
    }
}

// -----------------------
// 2) Lọc theo brand (chỉ laptop)
// -----------------------
if (!empty($brand) && $section === 'laptop') {
    $sql .= " AND brand = '". $conn->real_escape_string($brand) ."'";
}

// RUN QUERY
$res = $conn->query($sql);

if (!$res) {
    echo "<div class='col-12 text-danger'>Lỗi SQL: " . htmlspecialchars($conn->error) . "</div>";
    exit;
}

$html = "";

// -----------------------
// 3) Render mỗi SPU
// -----------------------
while ($spu = $res->fetch_assoc()) {

    // Lấy ảnh SKU chính
    $imgRow = $conn->query("
        SELECT image_url
        FROM sku_images
        WHERE sku_id = (SELECT id FROM sku WHERE spu_id = {$spu['id']} LIMIT 1)
        ORDER BY is_primary DESC, id ASC
        LIMIT 1
    ")->fetch_assoc();

    $img = $imgRow['image_url'] ?? "assets/images/no-image.png";

    // Lấy giá thấp nhất từ SKU
    $priceRow = $conn->query("
        SELECT MIN(COALESCE(promo_price, price)) AS min_price
        FROM sku
        WHERE spu_id = {$spu['id']}
    ")->fetch_assoc();

    $minPrice = $priceRow['min_price'] ?? 0;
    $minPriceFmt = $minPrice ? number_format($minPrice) . "₫" : "";

    // HTML hiển thị sản phẩm
    $html .= "
        <div class='col-md-3 mb-4'>
            <a href='product.php?spu_id={$spu['id']}' class='text-decoration-none text-dark'>
                <div class='card h-100 shadow-sm'>
                    <img src='{$img}' class='card-img-top' style='height:180px;object-fit:contain'>
                    <div class='card-body'>
                        <h6 class='card-title'>{$spu['brand']} {$spu['name']}</h6>
                        <div class='text-danger fw-bold'>{$minPriceFmt}</div>
                    </div>
                </div>
            </a>
        </div>
    ";
}

// output
echo $html !== "" 
    ? $html 
    : "<div class='col-12'><p class='text-muted'>Không có sản phẩm.</p></div>";

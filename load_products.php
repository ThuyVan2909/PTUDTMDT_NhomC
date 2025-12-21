<?php
include 'db.php';

// nhận dữ liệu filter từ AJAX
$section = $_POST['section'] ?? '';
$category = $_POST['category'] ?? '';
$brand = $_POST['brand'] ?? '';

// base SQL
$sql = "
SELECT 
    spu.id,
    spu.name,
    spu.brand,

    MIN(sku.price) AS original_price,
    MIN(COALESCE(sku.promo_price, sku.price)) AS final_price,

    COALESCE(ROUND(AVG(r.rating),1), 0) AS avg_rating,
    COUNT(DISTINCT r.id) AS review_count

FROM spu
JOIN sku ON sku.spu_id = spu.id
LEFT JOIN product_reviews r 
    ON r.spu_id = spu.id 
   AND r.rating BETWEEN 1 AND 5

WHERE 1
";


// -----------------------
// 1) Lọc category
// -----------------------
if (!empty($category)) {
    $cat = intval($category);

    // Lấy cả danh mục cha + danh mục con
    $sql .= " AND category_id IN (
        SELECT id FROM categories 
        WHERE id = $cat OR parent_id = $cat
    )";
}
else {
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
$sql .= " GROUP BY spu.id";
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
    $imgRow = $conn->query("
    SELECT image_url
    FROM sku_images
    WHERE sku_id = (
        SELECT id FROM sku WHERE spu_id = {$spu['id']} LIMIT 1
    )
    ORDER BY is_primary DESC, id ASC
    LIMIT 1
")->fetch_assoc();

$img = $imgRow['image_url'] ?? "assets/images/no-image.png";
$origin = (float)$spu['original_price'];
$final  = (float)$spu['final_price'];

$discountPercent = 0;
if ($origin > 0 && $final < $origin) {
    $discountPercent = round((($origin - $final) / $origin) * 100);
}


    // Lấy ảnh SKU chính
    


    // HTML hiển thị sản phẩm
    // THÊM ID CHO MỖI PRODUCT → scroll chính xác
    $html .= "
    <div class='col-md-3 mb-4' id='product-{$spu['id']}'>
        <a href='product.php?spu_id={$spu['id']}' class='text-decoration-none text-dark'>
            <div class='card h-100 shadow-sm position-relative'>

                ".($discountPercent > 0 ? "
                    <div class='discount-badge'>-{$discountPercent}%</div>
                " : "")."

                <img src='{$img}' class='card-img-top' style='height:180px;object-fit:contain'>

                <div class='card-body'>
                    <h6 class='card-title'>{$spu['brand']} {$spu['name']}</h6>

                    <div class='text-danger fw-bold'>
                        ".number_format($final)."₫
                        ".($discountPercent > 0 ? "
                            <small class='text-muted text-decoration-line-through ms-1'>
                                ".number_format($origin)."₫
                            </small>
                        " : "")."
                    </div>
                </div>

                ".($spu['avg_rating'] > 0 ? "
                    <div class='rating-box'>
                        ⭐ {$spu['avg_rating']} ({$spu['review_count']})
                    </div>
                " : "")."

            </div>
        </a>
    </div>
";

}

// output
echo $html !== "" 
    ? $html 
    : "<div class='col-12'><p class='text-muted'>Không có sản phẩm.</p></div>";

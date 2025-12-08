<?php
include 'db.php';

// section = laptop / phone / watch
$section = $_POST['section'] ?? '';
$category = $_POST['category'] ?? '';
$brand = $_POST['brand'] ?? '';

$sql = "SELECT * FROM spu WHERE 1";

// filter theo category
if ($category !== "" && $category !== null) {
    $sql .= " AND category_id = " . intval($category);
} else {
    // nếu không truyền category nhưng truyền section, ta có thể tự map section -> parent_id (tuỳ muốn)
    // ví dụ: nếu bạn muốn load tất cả sp thuộc parent category laptop (2) khi section='laptop'
    $map = ['laptop'=>2, 'phone'=>1, 'watch'=>3];
    if (isset($map[$section])) {
        $parent = intval($map[$section]);
        // lấy tất cả category id = parent OR parent_id = parent
        $sql .= " AND category_id IN (
            SELECT id FROM categories WHERE id = $parent OR parent_id = $parent
        )";
    }
}

// filter theo brand (chỉ dùng cho laptop)
if ($brand !== "" && $brand !== null) {
    $sql .= " AND brand = '". $conn->real_escape_string($brand) ."'";
}

$res = $conn->query($sql);
if (!$res) {
    echo "<div class='col-12 text-danger'>SQL Error: " . htmlspecialchars($conn->error) . "</div>";
    exit;
}

$html = "";
while ($spu = $res->fetch_assoc()) {

    // lấy ảnh SKU đầu tiên
    $imgRow = $conn->query("
        SELECT image_url FROM sku_images 
        WHERE sku_id = (SELECT id FROM sku WHERE spu_id = {$spu['id']} LIMIT 1)
        ORDER BY is_primary DESC, id ASC LIMIT 1
    ")->fetch_assoc();
    $img = $imgRow['image_url'] ?? "assets/images/no-image.png";

    // lấy giá thấp nhất (optional): show price từ sku
    $priceRow = $conn->query("SELECT MIN(COALESCE(promo_price, price)) AS min_price FROM sku WHERE spu_id = {$spu['id']}")->fetch_assoc();
    $minPrice = $priceRow['min_price'] ?? 0;
    $minPriceFmt = $minPrice ? number_format($minPrice) . "₫" : "";

    $html .= "
    <div class='col-md-3'>
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

if ($html === "") {
    echo "<div class='col-12'><p class='text-muted'>Không có sản phẩm.</p></div>";
} else {
    echo $html;
}

<?php
include 'db.php';
$conn->set_charset("utf8mb4");

/* ===== INPUT ===== */
$catId = intval($_POST['cat'] ?? 0);
$min   = intval($_POST['min'] ?? 0);
$max   = intval($_POST['max'] ?? 50000000);

$brand = trim($_POST['brand'] ?? '');
$type  = trim($_POST['type'] ?? '');

/* ===== BASE SQL ===== */
$sql = "
SELECT
    spu.id,
    spu.name,
    spu.brand,
    MIN(sku.price) AS original_price,
    MIN(COALESCE(sku.promo_price, sku.price)) AS final_price
FROM spu
JOIN sku ON sku.spu_id = spu.id
WHERE 1
";

/* ===== FILTER CATEGORY (click từ menu) ===== */
if ($catId > 0) {
    $sql .= "
    AND spu.category_id IN (
        SELECT id FROM categories
        WHERE id = $catId OR parent_id = $catId
    )
    ";
}

/* ===== FILTER TYPE (Điện thoại / Laptop) ===== */
if ($type === 'phone') {
    // Điện thoại = category id 1 + con
    $sql .= "
    AND spu.category_id IN (
        SELECT id FROM categories
        WHERE id = 1 OR parent_id = 1
    )
    ";
}

if ($type === 'laptop') {
    // Laptop = category id 2 + con
    $sql .= "
    AND spu.category_id IN (
        SELECT id FROM categories
        WHERE id = 2 OR parent_id = 2
    )
    ";
}

/* ===== FILTER BRAND ===== */
if ($brand !== '') {
    $brandSafe = $conn->real_escape_string($brand);
    $sql .= " AND spu.brand = '$brandSafe' ";
}

/* ===== FILTER PRICE ===== */
$sql .= "
AND COALESCE(sku.promo_price, sku.price) BETWEEN $min AND $max
GROUP BY spu.id
ORDER BY final_price ASC
";

/* ===== RUN ===== */
$res = $conn->query($sql);

if (!$res) {
    echo "<div class='col-12 text-danger'>SQL Error: ".htmlspecialchars($conn->error)."</div>";
    exit;
}

if ($res->num_rows === 0) {
    echo "<div class='col-12 text-muted'>Không có sản phẩm.</div>";
    exit;
}

/* ===== RENDER ===== */
while ($spu = $res->fetch_assoc()) {

    $imgRow = $conn->query("
        SELECT si.image_url
        FROM sku s
        JOIN sku_images si ON si.sku_id = s.id
        WHERE s.spu_id = {$spu['id']}
        ORDER BY si.is_primary DESC,
                 COALESCE(s.promo_price, s.price) ASC
        LIMIT 1
    ")->fetch_assoc();

    $img = $imgRow['image_url'] ?? 'assets/images/no-image.png';

    $origin = (float)$spu['original_price'];
    $final  = (float)$spu['final_price'];

    $discount = 0;
    if ($origin > 0 && $final < $origin) {
        $discount = round((($origin - $final) / $origin) * 100);
    }
    ?>

    <div class="col-6 col-md-4 col-xl-3">
        <a href="product.php?spu_id=<?= $spu['id'] ?>" class="text-decoration-none text-dark">
            <div class="product-card p-2 h-100 position-relative">

                <?php if ($discount > 0): ?>
                    <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                        -<?= $discount ?>%
                    </span>
                <?php endif; ?>

                <img src="<?= htmlspecialchars($img) ?>" class="product-img mb-2">

                <div class="product-name">
                    <?= htmlspecialchars($spu['brand'].' '.$spu['name']) ?>
                </div>

                <div class="mt-1">
                    <span class="price"><?= number_format($final) ?>₫</span>
                    <?php if ($discount > 0): ?>
                        <span class="old-price ms-1"><?= number_format($origin) ?>₫</span>
                    <?php endif; ?>
                </div>

            </div>
        </a>
    </div>

    <?php
}

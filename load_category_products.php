<?php
$conn = new mysqli("localhost","root","","lendly_db");

$catId = intval($_POST['cat'] ?? 0);
$min   = intval($_POST['min'] ?? 0);
$max   = intval($_POST['max'] ?? 50000000);

$sql = "
SELECT p.id, p.name, p.thumbnail,
       MIN(COALESCE(s.promo_price, s.price)) AS final_price
FROM products p
JOIN sku s ON p.id = s.spu_id
WHERE p.category_id = $catId
AND COALESCE(s.promo_price, s.price) BETWEEN $min AND $max
GROUP BY p.id
ORDER BY p.id DESC
";

$rs = $conn->query($sql);

if($rs->num_rows == 0){
    echo '<div class="col-12 text-muted">Không có sản phẩm phù hợp</div>';
    exit;
}

while($p = $rs->fetch_assoc()):
?>
<div class="col-6 col-md-4 col-lg-3">
  <div class="product-card h-100">
    <img src="<?= htmlspecialchars($p['thumbnail']) ?>" class="product-img">
    <div class="p-2">
      <div class="fw-semibold small"><?= htmlspecialchars($p['name']) ?></div>
      <div class="text-danger fw-bold mt-1">
        <?= number_format($p['final_price']) ?>₫
      </div>
    </div>
  </div>
</div>
<?php endwhile; ?>

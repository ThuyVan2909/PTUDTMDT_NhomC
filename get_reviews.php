<?php
include 'db.php';

$spu_id = (int)$_GET['spu_id'];
$star   = (int)($_GET['star'] ?? 0);

$sql = "
    SELECT r.*, u.fullname
    FROM product_reviews r
    LEFT JOIN users u ON r.user_id = u.id
    WHERE r.spu_id = $spu_id
";
if ($star > 0) {
    $sql .= " AND r.rating = $star";
}
$sql .= " ORDER BY r.created_at DESC";

$res = $conn->query($sql);

while ($rv = $res->fetch_assoc()):
?>
<div class="review-item">
    <div class="review-user"><?= htmlspecialchars($rv['fullname'] ?? 'Khách hàng') ?></div>
    <div class="star"><?= str_repeat('★', $rv['rating']) ?></div>
    <p><?= nl2br(htmlspecialchars($rv['comment'])) ?></p>
    <div class="review-date"><?= date("d/m/Y", strtotime($rv['created_at'])) ?></div>
</div>
<?php endwhile; ?>

<?php
include 'db.php';

$spu_id = (int)$_GET['spu_id'];
$rating = (int)$_GET['rating'];

$sql = "
SELECT r.*, u.fullname
FROM product_reviews r
JOIN users u ON u.id = r.user_id
WHERE r.spu_id = $spu_id
";

if ($rating > 0) {
    $sql .= " AND r.rating = $rating";
}

$sql .= " ORDER BY r.created_at DESC";

$res = $conn->query($sql);

while ($rv = $res->fetch_assoc()) {
    echo "
    <div class='review-item'>
        <div class='review-user'>{$rv['fullname']}</div>
        <div class='star'>".str_repeat('★', $rv['rating'])."</div>
        <div>{$rv['comment']}</div>
        <div class='review-date'>".date('d/m/Y', strtotime($rv['created_at']))."</div>
    </div>
    ";
}

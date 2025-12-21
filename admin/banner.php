<?php
include "../db.php";

$banners = $conn->query("
    SELECT *
    FROM banners
    ORDER BY position ASC, sort_order ASC
");
?>

<h2>Quản lý Banner</h2>

<a href="banner_add.php" class="btn btn-primary" style="margin-bottom:10px;">+ Thêm banner</a>

<table>
    <tr>
        <th>ID</th>
        <th>Tiêu đề</th>
        <th>Hình</th>
        <th>Link</th>
        <th>Vị trí</th>
        <th>Thứ tự</th>
        <th>Hiển thị</th>
        <th>Hành động</th>
    </tr>

<?php while ($b = $banners->fetch_assoc()): ?>
<tr>
    <td><?= $b['id'] ?></td>
    <td><?= htmlspecialchars($b['title']) ?></td>
    <td>
        <img src="../<?= $b['image_url'] ?>" style="height:60px">
    </td>
    <td><?= $b['link'] ?></td>
    <td><?= strtoupper($b['position']) ?></td>
    <td><?= $b['sort_order'] ?></td>
    <td><?= $b['is_active'] ? 'ON' : 'OFF' ?></td>
    <td>
        <a href="banner_edit.php?id=<?= $b['id'] ?>">Sửa</a> |
        <a href="banner_delete.php?id=<?= $b['id'] ?>"
           onclick="return confirm('Xóa banner này?')">Xóa</a>
    </td>
</tr>
<?php endwhile; ?>
</table>

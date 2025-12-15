<?php
include '../../db.php';
$sku_id = intval($_GET['sku_id']);
$imgs = $conn->query("SELECT * FROM sku_images WHERE sku_id=$sku_id");
?>

<h3>SKU Images (SKU ID: <?= $sku_id ?>)</h3>
<a href="../sku_images_add.php?sku_id=<?= $sku_id ?>" target="_blank">+ Add Image</a>

<table border="1" cellpadding="8" style="margin-top:10px;width:100%;">
    <tr><th>ID</th><th>Image</th><th>Primary</th><th></th></tr>
    <?php while($i = $imgs->fetch_assoc()) { ?>
    <tr>
        <td><?= $i['id'] ?></td>
        <td><img src="../../<?= $i['image_url'] ?>" width="80"></td>
        <td><?= $i['is_primary'] ?></td>
        <td>
            <a href="../sku_images_edit.php?id=<?= $i['id'] ?>" target="_blank">Edit</a>
        </td>
    </tr>
    <?php } ?>
</table>

<button onclick="closeModal()">Close</button>

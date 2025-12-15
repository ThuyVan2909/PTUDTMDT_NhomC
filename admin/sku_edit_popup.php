<?php
include '../db.php';

$id = intval($_GET['id']);
$sku = $conn->query("SELECT * FROM sku WHERE id=$id")->fetch_assoc();
?>

<h3>Edit SKU #<?= $id ?></h3>

<form method="post" action="sku_edit.php">
    <input type="hidden" name="id" value="<?= $id ?>">

    SKU Code:<br>
    <input type="text" name="sku_code" value="<?= $sku['sku_code'] ?>" style="width:100%"><br><br>

    Variant (JSON):<br>
    <textarea name="variant" style="width:100%;height:80px"><?= $sku['variant'] ?></textarea><br><br>

    Price:<br>
    <input type="number" name="price" value="<?= $sku['price'] ?>"><br><br>

    Promo Price:<br>
    <input type="number" name="promo_price" value="<?= $sku['promo_price'] ?>"><br><br>

    Stock:<br>
    <input type="number" name="stock" value="<?= $sku['stock'] ?>"><br><br>

    Warehouse:<br>
    <input type="text" name="warehouse_location" value="<?= $sku['warehouse_location'] ?>" style="width:100%"><br><br>

    <button type="submit">Save</button>
    <button type="button" onclick="closeModal()">Close</button>
</form>

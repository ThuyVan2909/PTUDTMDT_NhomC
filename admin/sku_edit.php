<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $spu_id = intval($_POST['spu_id']); // Lấy từ hidden input
    $sku_code = $_POST['sku_code'];
    $variant = $_POST['variant'];
    $price = floatval($_POST['price']);
    $promo_price = floatval($_POST['promo_price']);
    $stock = intval($_POST['stock']);
    $warehouse = $_POST['warehouse_location'];

    $stmt = $conn->prepare("
        UPDATE sku SET 
            sku_code=?, 
            variant=?, 
            price=?, 
            promo_price=?, 
            stock=?, 
            warehouse_location=? 
        WHERE id=?
    ");

    $stmt->bind_param(
        "ssddisi", 
        $sku_code, 
        $variant,
        $price,
        $promo_price,
        $stock,
        $warehouse,
        $id
    );

    if ($stmt->execute()) {
        // Redirect về trang product_edit đúng SPU
        echo "<script>
            alert('Cập nhật thành công!');
            window.location='product_edit.php?id=$spu_id';
        </script>";
        exit;
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

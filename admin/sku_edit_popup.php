<?php
include '../db.php';

$id = intval($_GET['id']);
$sku = $conn->query("SELECT * FROM sku WHERE id=$id")->fetch_assoc();
?>



<style>
/* ===== SKU POPUP FORM ===== */
.sku-form {
    font-size: 14px;
}

.sku-form .form-label {
    font-weight: 600;
    color: #1A3D64;
}

.sku-form .form-control:focus {
    border-color: #1A3D64;
    box-shadow: 0 0 0 0.15rem rgba(26, 61, 100, 0.25);
}

.variant-box {
    font-family: monospace;
    background: #f8f9fa;
}

h3.text-primary {
    color: #1A3D64 !important;
    font-weight: 700;
}

</style>
<h3 class="mb-3 text-primary">
    <i class="bi bi-pencil-square"></i> Chỉnh sửa SKU #<?= $id ?>
</h3>

<form method="post" action="sku_edit.php" class="sku-form">
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="hidden" name="spu_id" value="<?= $sku['spu_id'] ?>"> <!-- thêm dòng này -->

    <div class="mb-3">
        <label class="form-label">SKU Code</label>
        <input type="text" name="sku_code"
               class="form-control"
               value="<?= htmlspecialchars($sku['sku_code']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Variant (JSON)</label>
        <textarea name="variant"
                  class="form-control variant-box"
                  rows="4"><?= htmlspecialchars($sku['variant']) ?></textarea>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Giá</label>
            <input type="number" name="price"
                   class="form-control"
                   value="<?= $sku['price'] ?>">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Giá ưu đãi</label>
            <input type="number" name="promo_price"
                   class="form-control"
                   value="<?= $sku['promo_price'] ?>">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Tồn kho</label>
            <input type="number" name="stock"
                   class="form-control"
                   value="<?= $sku['stock'] ?>">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Kho hàng</label>
            <input type="text" name="warehouse_location"
                   class="form-control"
                   value="<?= htmlspecialchars($sku['warehouse_location']) ?>">
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-4">
        <button type="button" class="btn btn-outline-secondary"
                onclick="closeModal()">
            <i class="bi bi-x-circle"></i> Đóng
        </button>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Lưu SKU
        </button>
    </div>
</form>
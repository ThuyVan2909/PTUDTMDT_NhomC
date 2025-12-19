<?php
include "../db.php";
$coupons = $conn->query("SELECT * FROM coupons ORDER BY id DESC");
?>

<div class="card">
    <h3>Quản lý Coupon</h3>

    <table id="couponTable">
        <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Giảm (₫)</th>
            <th>Đơn tối thiểu (₫)</th>
            <th>Hết hạn</th>
            <th>Hành động</th>
        </tr>

        <?php while ($c = $coupons->fetch_assoc()): ?>
        <tr data-id="<?= $c['id'] ?>">
            <td><?= $c['id'] ?></td>
            <td class="code"><?= $c['code'] ?></td>
            <td class="discount"><?= $c['discount_amount'] ?></td>
            <td class="min"><?= $c['min_order_total'] ?></td>
            <td class="expired"><?= $c['expired_at'] ?></td>
            <td>
                <button onclick="openEdit(this)">Sửa</button>
                <a href="coupon_delete.php?id=<?= $c['id'] ?>"
                   onclick="return confirm('Xóa coupon này?')">Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- MODAL -->
<div id="editModal" style="display:none">
    <div class="modal">
        <h3>Sửa Coupon</h3>
        <input type="hidden" id="edit_id">

        Code:<br>
        <input id="edit_code"><br>

        Giảm giá (₫):<br>
        <input type="number" id="edit_discount"><br>

        Đơn tối thiểu (₫):<br>
        <input type="number" id="edit_min"><br>

        Hết hạn:<br>
        <input type="datetime-local" id="edit_expired"><br><br>

        <button onclick="saveEdit()">Lưu</button>
        <button onclick="closeEdit()">Hủy</button>
    </div>
</div>

<style>
#editModal {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.5);
    display: flex;
    justify-content: center;
    align-items: center;
}
.modal {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    width: 350px;
}
</style>

<script>
let currentRow = null;

function openEdit(btn) {
    currentRow = btn.closest('tr');

    document.getElementById('edit_id').value =
        currentRow.dataset.id;

    document.getElementById('edit_code').value =
        currentRow.querySelector('.code').innerText;

    document.getElementById('edit_discount').value =
        currentRow.querySelector('.discount').innerText;

    document.getElementById('edit_min').value =
        currentRow.querySelector('.min').innerText;

    let exp = currentRow.querySelector('.expired').innerText
        .replace(' ', 'T').substring(0,16);

    document.getElementById('edit_expired').value = exp;

    document.getElementById('editModal').style.display = 'flex';
}

function closeEdit() {
    document.getElementById('editModal').style.display = 'none';
}

function saveEdit() {
    let form = new FormData();
    form.append('id', document.getElementById('edit_id').value);
    form.append('code', document.getElementById('edit_code').value);
    form.append('discount_amount', document.getElementById('edit_discount').value);
    form.append('min_order_total', document.getElementById('edit_min').value);
    form.append('expired_at', document.getElementById('edit_expired').value);

    fetch('coupon_update.php', {
        method: 'POST',
        body: form
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            currentRow.querySelector('.code').innerText = res.data.code;
            currentRow.querySelector('.discount').innerText = res.data.discount_amount;
            currentRow.querySelector('.min').innerText = res.data.min_order_total;
            currentRow.querySelector('.expired').innerText = res.data.expired_at;

            closeEdit();
        } else {
            alert(res.error);
        }
    });
}
</script>

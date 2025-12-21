<?php
include "../db.php";
$coupons = $conn->query("SELECT * FROM coupons ORDER BY id DESC");
?>

<div class="card">
    <h3>Quản lý Coupon</h3>
    <a href="#" id="addCouponBtn" class="btn btn-primary" style="margin-bottom:12px;">
    + Thêm coupon
</a>

<!-- MODAL ADD COUPON -->
<div id="addCouponModal" style="
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.7);
    justify-content:center;
    align-items:center;
    z-index:9999;
">
    <div style="
        background:#fff;
        padding:20px;
        border-radius:10px;
        width:350px;
        position:relative;
    ">
        <span id="closeAddCoupon" style="
            position:absolute; top:10px; right:14px;
            cursor:pointer; font-weight:bold; font-size:20px;">&times;</span>

        <h3>Thêm coupon mới</h3>

        <form id="addCouponForm" style="display:flex; flex-direction:column; gap:10px;">
            Code:
            <input type="text" name="code" required>

            Giảm giá (₫):
            <input type="number" name="discount_amount" required>

            Đơn tối thiểu (₫):
            <input type="number" name="min_order_total" required>

            Hết hạn:
            <input type="datetime-local" name="expired_at" required>

            <div style="display:flex; gap:10px; margin-top:10px;">
                <button type="submit" style="background:#135071;color:#fff;padding:6px 12px;border:none;border-radius:5px;cursor:pointer;">
                    Lưu
                </button>
                <button type="button" id="cancelAddCoupon" style="background:#ccc;color:#333;padding:6px 12px;border:none;border-radius:5px;cursor:pointer;">
                    Hủy
                </button>
            </div>
        </form>
    </div>
</div>

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

        Mã coupon:<br>
        <input id="edit_code"><br>

        Giảm giá (đ):<br>
        <input type="number" id="edit_discount"><br>

        Đơn tối thiểu (đ):<br>
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

const addBtn = document.getElementById('addCouponBtn');
const addModal = document.getElementById('addCouponModal');
const closeAdd = document.getElementById('closeAddCoupon');
const cancelAdd = document.getElementById('cancelAddCoupon');

addBtn.addEventListener('click', e => {
    e.preventDefault();
    addModal.style.display = 'flex';
});

closeAdd.addEventListener('click', () => addModal.style.display = 'none');
cancelAdd.addEventListener('click', () => addModal.style.display = 'none');
addModal.addEventListener('click', e => {
    if(e.target === addModal) addModal.style.display = 'none';
});

// AJAX submit
document.getElementById('addCouponForm').addEventListener('submit', function(e){
    e.preventDefault();
    const formData = new FormData(this);

    fetch('coupon_add.php', {
        method:'POST',
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if(res.success){
            alert("Đã thêm coupon!");
            addModal.style.display = 'none';
            location.reload(); // reload bảng coupon
        } else {
            alert(res.error);
        }
    });
});

</script>

<?php
include "../db.php";

/**
 * Lấy tên SPU từ link product.php?spu_id=X
 */
function getSpuNameFromLink(mysqli $conn, string $link): ?string {
    if (preg_match('/spu_id=(\d+)/', $link, $m)) {
        $spu_id = (int)$m[1];
        $rs = $conn->query("SELECT name FROM spu WHERE id = $spu_id LIMIT 1");
        if ($rs && $rs->num_rows) {
            return $rs->fetch_assoc()['name'];
        }
    }
    return null;
}

$banners = $conn->query("
    SELECT *
    FROM banners
    ORDER BY position ASC, sort_order ASC
");
?>

<h2>Quản lý Banner</h2>

<a href="banner_add.php" class="btn btn-primary" style="margin-bottom:12px;">
    Thêm banner
</a>
<!-- ================= ADD BANNER MODAL ================= -->
<div id="bannerModal" style="
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
        max-width:600px;
        width:100%;
        position:relative;
    ">
        <span id="closeBannerModal" style="
            position:absolute; top:10px; right:14px;
            cursor:pointer; font-weight:bold; font-size:20px;">&times;</span>

        <div id="bannerModalContent">Loading...</div> <!-- Form load ở đây -->
    </div>
</div>


<table id="bannerTable">
    <tr>
        <th>ID</th>
        <th>Tiêu đề</th>
        <th>Hình</th>
        <th>Sản phẩm</th>
        <th>Vị trí</th>
        <th>Thứ tự</th>
        <th>Hiển thị</th>
        <th>Hành động</th>
    </tr>

<?php while ($b = $banners->fetch_assoc()): ?>
<?php $spuName = getSpuNameFromLink($conn, $b['link']); ?>

<tr draggable="true" data-id="<?= $b['id'] ?>">
    <td><?= $b['id'] ?></td>

    <td><?= htmlspecialchars($b['title']) ?></td>

    <td>
        <img
            src="../<?= htmlspecialchars($b['image_url']) ?>"
            style="height:60px;cursor:pointer"
            onclick="openPreview('../<?= htmlspecialchars($b['image_url']) ?>')"
            title="Xem ảnh lớn"
        >
    </td>

    <td>
        <?= $spuName
            ? htmlspecialchars($spuName)
            : '<span style="color:#999">Không gắn sản phẩm</span>' ?>
    </td>

    <td><?= strtoupper($b['position']) ?></td>

    <td><?= (int)$b['sort_order'] ?></td>

    <td><?= $b['is_active'] ? 'ON' : 'OFF' ?></td>

    <td>
        <a href="banner_edit.php?id=<?= $b['id'] ?>">Sửa</a> |
        <a href="banner_delete.php?id=<?= $b['id'] ?>" 
        onclick="return confirm('Bạn có chắc muốn xóa banner này?')">
   Xóa
</a>

    </td>
</tr>
<?php endwhile; ?>
</table>

<!-- ================= PREVIEW MODAL ================= -->
<div id="imageModal" style="
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.7);
    justify-content:center;
    align-items:center;
    z-index:9999;
">
    <div style="
        position:relative;
        background:#fff;
        padding:12px;
        border-radius:6px;
        max-width:90%;
        max-height:90%;
    ">
        <!-- Nút thoát -->
        <button onclick="closePreview()" style="
            position:absolute;
            top:6px;
            right:8px;
            border:none;
            background:transparent;
            font-size:22px;
            cursor:pointer;
        ">✕</button>

        <img id="modalImg" style="
            max-width:100%;
            max-height:80vh;
            display:block;
        ">
    </div>
</div>

<script>
/* ================= PREVIEW ================= */
function openPreview(src) {
    document.getElementById("modalImg").src = src;
    document.getElementById("imageModal").style.display = "flex";
}

function closePreview() {
    document.getElementById("imageModal").style.display = "none";
}

/* Click nền đen để thoát */
document.getElementById("imageModal").addEventListener("click", e => {
    if (e.target.id === "imageModal") {
        closePreview();
    }
});

/* ================= DRAG & DROP SORT ================= */
let draggedRow = null;

document.querySelectorAll("#bannerTable tr[data-id]").forEach(row => {
    row.addEventListener("dragstart", () => draggedRow = row);
    row.addEventListener("dragover", e => e.preventDefault());
    row.addEventListener("drop", () => {
        if (draggedRow && draggedRow !== row) {
            row.parentNode.insertBefore(draggedRow, row);
            updateOrder();
        }
    });
});

function updateOrder() {
    const ids = [...document.querySelectorAll("#bannerTable tr[data-id]")]
        .map((tr, index) => ({
            id: tr.dataset.id,
            order: index + 1
        }));

    fetch("banner_sort.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify(ids)
    });
}


const addBtn = document.querySelector("a.btn.btn-primary"); // nút + Thêm banner
const bannerModal = document.getElementById("bannerModal");
const bannerContent = document.getElementById("bannerModalContent");
const closeBannerBtn = document.getElementById("closeBannerModal");

addBtn.addEventListener("click", e => {
    e.preventDefault(); // không chuyển trang

    fetch('banner_add.php')
        .then(res => res.text())
        .then(html => {
            bannerContent.innerHTML = html;
            bannerModal.style.display = "flex";

            // Preview ảnh
            const input = bannerContent.querySelector("input[type=file]");
            const preview = bannerContent.querySelector("#preview");
            if(input && preview){
                input.addEventListener("change", () => {
                    const file = input.files[0];
                    if(file){
                        preview.src = URL.createObjectURL(file);
                        preview.style.display = "block";
                    }
                });
            }

            // AJAX submit form
            const form = bannerContent.querySelector("form");
            form.addEventListener("submit", function(e){
                e.preventDefault();
                const data = new FormData(form);
                fetch('banner_add.php', {
                    method: 'POST',
                    body: data
                })
                .then(res => res.text())
                .then(resp => {
                    alert("Đã thêm banner!");
                    bannerModal.style.display = "none";
                    location.reload(); // reload bảng banner
                })
                .catch(err => console.error(err));
            });
        });
});

// Đóng modal
closeBannerBtn.addEventListener("click", () => bannerModal.style.display = "none");
bannerModal.addEventListener("click", e => {
    if(e.target === bannerModal) bannerModal.style.display = "none";
});


</script>



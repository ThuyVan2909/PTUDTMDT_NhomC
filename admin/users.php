<?php
include "../db.php";

$users = $conn->query("
    SELECT id, fullname, email, role, created_at
    FROM users
    ORDER BY id DESC
");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Quản lý người dùng</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/admin-user.css">
</head>

<body class="bg-light">
<div class="container py-4">

<h2 class="mb-4">Quản lý người dùng</h2>

<table class="table order-table table-striped table-hover align-middle">
<thead class="table-dark text-center">
<tr>
    <th>ID</th>
    <th>Họ tên</th>
    <th>Email</th>
    <th>Role</th>
    <th>Ngày tạo</th>
    <th width="220">Thao tác</th>
</tr>
</thead>

<tbody>
<?php while ($u = $users->fetch_assoc()): ?>
<tr>
<form method="POST" action="user_update.php">
    <td><?= $u['id'] ?><input type="hidden" name="id" value="<?= $u['id'] ?>"></td>

    <td><input class="form-control form-control-sm" name="fullname" value="<?= htmlspecialchars($u['fullname']) ?>" required></td>

    <td><input class="form-control form-control-sm" name="email" value="<?= htmlspecialchars($u['email']) ?>" required></td>

    <td>
        <?php
        $roleClass = $u['role'] === 'admin' ? 'role-admin' : 'role-customer';
        ?>
        <select name="role" class="form-select form-select-sm role-select <?= $roleClass ?>">
            <option value="customer" <?= $u['role']=='customer'?'selected':'' ?>>Customer</option>
            <option value="admin" <?= $u['role']=='admin'?'selected':'' ?>>Admin</option>
        </select>
    </td>

    <td><?= $u['created_at'] ?></td>

    <td class="text-center">
        <button class="btn btn-sm btn-save">Lưu</button>

        <?php if ($u['id'] != $_SESSION['user_id']): ?>
            <form method="POST" action="user_delete.php" class="d-inline">
                <input type="hidden" name="id" value="<?= $u['id'] ?>">
                <button class="btn btn-sm btn-delete"
                        onclick="return confirm('Xóa user này?')">Xóa</button>
            </form>
        <?php endif; ?>
    </td>
</form>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</div>
<script>
document.querySelectorAll('.role-select').forEach(select => {
    select.addEventListener('change', function () {

        this.classList.remove('role-admin', 'role-customer');

        if (this.value === 'admin') {
            this.classList.add('role-admin');
        } else {
            this.classList.add('role-customer');
        }
    });
});
</script>

</body>
</html>

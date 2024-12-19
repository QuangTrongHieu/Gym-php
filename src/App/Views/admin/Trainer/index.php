<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error']; ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Quản lý Huấn luyện viên</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
        Thêm Huấn luyện viên
    </button>
</div>

<?php if (!isset($trainers) || !is_array($trainers)): ?>
    <div class="alert alert-warning">Không có dữ liệu huấn luyện viên.</div>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Username</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Địa chỉ</th>
                <th>Lương</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($trainers as $trainer): ?>
            <tr>
                <td><?= $trainer['id'] ?></td>
                <td><?= $trainer['fullName'] ?></td>
                <td><?= $trainer['username'] ?></td>
                <td><?= $trainer['email'] ?></td>
                <td><?= $trainer['phone'] ?></td>
                <td><?= $trainer['address'] ?></td>
                <td><?= number_format($trainer['salary']) ?> VNĐ</td>
                <td>
                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $trainer['id'] ?>">Sửa</button>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $trainer['id'] ?>">Xóa</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php 
require_once ROOT_PATH . '/src/App/Views/admin/Trainer/create.php';
require_once ROOT_PATH . '/src/App/Views/admin/Trainer/edit.php';
require_once ROOT_PATH . '/src/App/Views/admin/Trainer/delete.php';
?>
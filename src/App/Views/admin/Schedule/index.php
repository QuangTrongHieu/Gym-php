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
    <h1>Quản lý Nhân viên</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
        Thêm Nhân viên
    </button>
</div>

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
        <?php foreach ($Trainers as $Trainer): ?>
        <tr>
            <td><?= $Trainer['id'] ?></td>
            <td><?= $Trainer['fullName'] ?></td>
            <td><?= $Trainer['username'] ?></td>
            <td><?= $Trainer['email'] ?></td>
            <td><?= $Trainer['phone'] ?></td>
            <td><?= $Trainer['address'] ?></td>
            <td><?= number_format($Trainer['salary']) ?> VNĐ</td>
            <td>
                <button type="button" 
                        class="btn btn-sm btn-warning" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editModal<?= $Trainer['id'] ?>">
                    Sửa
                </button>
                <button type="button" 
                        class="btn btn-sm btn-danger" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteModal<?= $Trainer['id'] ?>">
                    Xóa
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php 
require_once ROOT_PATH . '/src/App/Views/admin/ScheduleManagement/create.php';
require_once ROOT_PATH . '/src/App/Views/admin/ScheduleManagement/edit.php';
require_once ROOT_PATH . '/src/App/Views/admin/ScheduleManagement/delete.php';
?>

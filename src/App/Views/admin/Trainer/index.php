
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= $_SESSION['success']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= $_SESSION['error']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php
// Base64 encoded default avatar - simple user icon
$defaultAvatarBase64 = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+PHJlY3Qgd2lkdGg9IjEwMCIgaGVpZ2h0PSIxMDAiIGZpbGw9IiNlOWVjZWYiLz48Y2lyY2xlIGN4PSI1MCIgY3k9IjM1IiByPSIyMCIgZmlsbD0iI2FkYjViZCIvPjxwYXRoIGQ9Ik0xNSw4NWMwLTIwLDE1LTM1LDM1LTM1czM1LDE1LDM1LDM1IiBmaWxsPSIjYWRiNWJkIi8+PC9zdmc+';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Quản lý Huấn luyện viên</h1>
    <div>
        <a href="/gym-php/admin/trainer/export" class="btn btn-success me-2">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Thêm Huấn luyện viên
        </button>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Họ tên</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Chuyên môn</th>
                        <th>Lương</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($trainer)): ?>
                        <?php foreach($trainer as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['id']) ?></td>
                            <td>
                                <?php
                                if (!empty($item['avatar']) && $item['avatar'] !== 'default.jpg') {
                                    $avatarPath = "/gym-php/public/uploads/trainers/" . htmlspecialchars($item['avatar']);
                                } else {
                                    $avatarPath = $defaultAvatarBase64;
                                }
                                ?>
                                <img src="<?= $avatarPath ?>" 
                                     class="trainer-avatar"
                                     alt="<?= htmlspecialchars($item['fullName']) ?>"
                                     loading="lazy">
                            </td>
                            <td><?= htmlspecialchars($item['fullName']) ?></td>
                            <td><?= htmlspecialchars($item['username']) ?></td>
                            <td><?= htmlspecialchars($item['email']) ?></td>
                            <td><?= htmlspecialchars($item['phone']) ?></td>
                            <td><?= htmlspecialchars($item['specialization']) ?></td>
                            <td><?= number_format($item['salary'], 0, ',', '.') ?> VNĐ</td>
                            <td>
                                <div class="action-buttons">
                                    <button type="button" 
                                            class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal<?= $item['id'] ?>"
                                            title="Sửa thông tin">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm"
                                            onclick="showDeleteModal(<?= $item['id'] ?>, '<?= htmlspecialchars(addslashes($item['fullName'])) ?>')"
                                            title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-info-circle text-info me-2"></i>
                                Không có dữ liệu huấn luyện viên
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.trainer-avatar {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid #ddd;
    transition: transform 0.2s ease;
    background-color: #f8f9fa;
}

.trainer-avatar:hover {
    transform: scale(1.1);
    border-color: #007bff;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.action-buttons .btn {
    padding: 0.25rem 0.5rem;
}

.table td {
    vertical-align: middle;
}
</style>

<?php 
require_once ROOT_PATH . '/src/App/Views/admin/Trainer/edit.php';
require_once ROOT_PATH . '/src/App/Views/admin/Trainer/create.php';
require_once ROOT_PATH . '/src/App/Views/admin/Trainer/delete.php';
?>
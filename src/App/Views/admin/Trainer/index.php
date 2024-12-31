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

// Function to get avatar URL with validation
function getAvatarUrl($avatar) {
    if (empty($avatar)) {
        return null;
    }
    
    // Nếu là default.jpg, trả về đường dẫn mặc định
    if ($avatar === 'default.jpg') {
        $defaultPath = ROOT_PATH . '/public/uploads/trainers/default.jpg';
        if (file_exists($defaultPath)) {
            return '/gym-php/public/uploads/trainers/default.jpg';
        }
        return null;
    }
    
    // Kiểm tra và tạo thư mục nếu chưa tồn tại
    $uploadDir = ROOT_PATH . '/public/uploads/trainers';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $avatarPath = $uploadDir . '/' . $avatar;
    if (!file_exists($avatarPath)) {
        error_log("Avatar not found: " . $avatarPath);
        return null;
    }
    
    // Kiểm tra quyền đọc file
    if (!is_readable($avatarPath)) {
        error_log("Avatar not readable: " . $avatarPath);
        return null;
    }
    
    return '/gym-php/public/uploads/trainers/' . htmlspecialchars($avatar);
}
?>

<style>
    .trainer-avatar {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #e9ecef;
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
                        <?php foreach ($trainer as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['id']) ?></td>
                                <td>
                                    <?php
                                    $avatarUrl = getAvatarUrl($item['avatar']);
                                    if ($avatarUrl) {
                                        echo '<img src="' . $avatarUrl . '" 
                                                  class="trainer-avatar" 
                                                  alt="Avatar of ' . htmlspecialchars($item['fullName']) . '"
                                                  onerror="this.src=\'' . $defaultAvatarBase64 . '\'">';
                                    } else {
                                        echo '<img src="' . $defaultAvatarBase64 . '" 
                                                  class="trainer-avatar" 
                                                  alt="Default Avatar">';
                                    }
                                    ?>
                                </td>
                                <td><?= htmlspecialchars($item['fullName']) ?></td>
                                <td><?= htmlspecialchars($item['username']) ?></td>
                                <td><?= htmlspecialchars($item['email']) ?></td>
                                <td><?= htmlspecialchars($item['phone']) ?></td>
                                <td><?= htmlspecialchars($item['specialization']) ?></td>
                                <td><?= number_format($item['salary'], 0, ',', '.') ?> VNĐ</td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-primary btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal<?= $item['id'] ?>">
                                        <i class="fas fa-edit"></i> Sửa
                                    </button>
                                    <button type="button"
                                        class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal"
                                        onclick="prepareDelete(<?= $item['id'] ?>, '<?= htmlspecialchars($item['fullName'], ENT_QUOTES) ?>')">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </button>
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

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Xóa Huấn luyện viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Bạn có chắc chắn muốn xóa huấn luyện viên <strong id="deleteTrainerName"></strong> không?
                    <br>
                    <small class="text-muted">Hành động này không thể hoàn tác. Tất cả dữ liệu liên quan sẽ bị xóa vĩnh viễn.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Xác nhận xóa
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function prepareDelete(id, name) {
        document.getElementById('deleteTrainerName').textContent = name;
        document.getElementById('deleteForm').action = `/gym-php/admin/trainer/delete/${id}`;
    }
</script>

<?php
require_once ROOT_PATH . '/src/App/Views/admin/Trainer/edit.php';
require_once ROOT_PATH . '/src/App/Views/admin/Trainer/create.php';
require_once ROOT_PATH . '/src/App/Views/admin/Trainer/delete.php';
?>
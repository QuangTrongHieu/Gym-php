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
    <h1 class="h3 mb-0">Quản lý Hội viên</h1>
    <div>
        <a href="/gym-php/admin/member/export" class="btn btn-success me-2">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Thêm Hội viên
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
                        <th>Gói tập</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($members)): ?>
                        <?php foreach ($members as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['id']) ?></td>
                                <td>
                                    <img src="<?= $item['avatarUrl'] ?? $defaultAvatarBase64 ?>"
                                        class="member-avatar"
                                        alt="<?= htmlspecialchars($item['fullName']) ?>"
                                        loading="lazy">
                                </td>
                                <td><?= htmlspecialchars($item['fullName']) ?></td>
                                <td><?= htmlspecialchars($item['username']) ?></td>
                                <td><?= htmlspecialchars($item['email']) ?></td>
                                <td><?= htmlspecialchars($item['phone']) ?></td>
                                <td>
                                    <?php if (!empty($item['package_name'])): ?>
                                        <div class="package-info">
                                            <span class="package-name"><?= htmlspecialchars($item['package_name']) ?></span>
                                            <small class="text-muted d-block">
                                                <?= date('d/m/Y', strtotime($item['startDate'])) ?> -
                                                <?= date('d/m/Y', strtotime($item['endDate'])) ?>
                                            </small>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Chưa đăng ký</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($item['membership_status'])): ?>
                                        <span class="badge <?= $item['membership_status'] == 'active' ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $item['membership_status'] == 'active' ? 'Đang hoạt động' : 'Hết hạn' ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Chưa đăng ký</span>
                                    <?php endif; ?>
                                </td>
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
                                Không có dữ liệu hội viên
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
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

    .package-info {
        line-height: 1.2;
    }

    .package-name {
        font-weight: 500;
    }

    .badge {
        padding: 0.5em 0.75em;
    }
</style>

<?php
require_once ROOT_PATH . '/src/App/Views/admin/member/edit.php';
require_once ROOT_PATH . '/src/App/Views/admin/member/create.php';
require_once ROOT_PATH . '/src/App/Views/admin/member/delete.php';
?>
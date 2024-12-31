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
    <h1>Quản lý Hội viên</h1>
    <div>
        <a href="/gym-php/admin/member-management/export" class="btn btn-success me-2">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Thêm Hội viên
        </button>
    </div>
</div>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Ảnh</th>
            <th>Họ tên</th>
            <th>Username</th>
            <th>Email</th>
            <th>SĐT</th>
            <th>Gói tập</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($members)): ?>
            <?php foreach($members as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td>
                    <div class="avatar-container">
                        <img src="<?= empty($item['avatar']) || $item['avatar'] === 'default.jpg' 
                            ? 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMzEgMjMxIj48cGF0aCBkPSJNMzMuODMsMzMuODNhMTE1LjUsMTE1LjUsMCwxLDEsMCwxNjMuMzQsMTE1LjQ5LDExNS40OSwwLDAsMSwwLTE2My4zNFpNMTE1LjUsMTkyLjc1YTc3LjI1LDc3LjI1LDAsMCwwLDQ3LjY3LTE2LjRsLTYuOTQtNDEuNjZhMTUuMzYsMTUuMzYsMCwwLDAtMTUuMTEtMTIuOTRIODkuODhhMTUuMzYsMTUuMzYsMCwwLDAtMTUuMTEsMTIuOTLMNjcuODMsMTc2LjM1QTc3LjI1LDc3LjI1LDAsMCwwLDExNS41LDE5Mi43NVptMC0xNTQuNWE3Ny4yNSw3Ny4yNSwwLDAsMC00Ny42NywxNi40bDYuOTQsNDEuNjZhMTUuMzYsMTUuMzYsMCwwLDAsMTUuMTEsMTIuOTRoNTEuMjRhMTUuMzYsMTUuMzYsMCwwLDAsMTUuMTEtMTIuOTRsNi45NC00MS42NkE3Ny4yNSw3Ny4yNSwwLDAsMCwxMTUuNSwzOC4yNVoiLz48L3N2Zz4=' 
                            : '/gym-php/public/uploads/members/' . $item['avatar'] ?>" 
                            alt="<?= htmlspecialchars($item['fullName']) ?>" 
                            class="rounded-circle"
                            style="width: 50px; height: 50px; object-fit: cover;">
                    </div>
                </td>
                <td><?= htmlspecialchars($item['fullName']) ?></td>
                <td><?= htmlspecialchars($item['username']) ?></td>
                <td><?= htmlspecialchars($item['email']) ?></td>
                <td><?= htmlspecialchars($item['phone']) ?></td>
                <td>
                    <?php if (!empty($item['package_name'])): ?>
                        <?= htmlspecialchars($item['package_name']) ?>
                        <br>
                        <small class="text-muted">
                            <?= date('d/m/Y', strtotime($item['startDate'])) ?> - 
                            <?= date('d/m/Y', strtotime($item['endDate'])) ?>
                        </small>
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
                    <div class="btn-group">
                        <a href="/gym-php/admin/member-management/update/<?= $item['id'] ?>" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger" 
                                onclick="confirmDelete(<?= $item['id'] ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9">Không có dữ liệu</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Delete Modal -->
<div class="modal fade" id="deleteMemberModal" tabindex="-1" aria-labelledby="deleteMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteMemberModalLabel">Xóa Hội viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Bạn có chắc chắn muốn xóa hội viên này không?
                    <br>
                    <small class="text-muted">Hành động này không thể hoàn tác.</small>
                </div>
                <form id="deleteMemberForm" method="POST">
                    <input type="hidden" id="deleteMemberId" name="id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <button type="submit" class="btn btn-danger" form="deleteMemberForm">
                    <i class="fas fa-trash-alt"></i> Xác nhận xóa
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    showDeleteModal(id);
}

function showDeleteModal(id) {
    document.getElementById('deleteMemberId').value = id;
    document.getElementById('deleteMemberForm').action = `/gym-php/admin/member-management/delete/${id}`;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteMemberModal'));
    deleteModal.show();
}

function submitDeleteForm() {
    document.getElementById('deleteMemberForm').submit();
}
</script>

<style>
.avatar-container {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #dee2e6;
    background-color: #f8f9fa;
}

.member-avatar {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.2s ease-in-out;
}

.avatar-container:hover .member-avatar {
    transform: scale(1.1);
}

.badge {
    font-size: 0.875rem;
    padding: 0.5em 0.75em;
}

.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.modal-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.modal-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}
</style>

<?php 
require_once ROOT_PATH . '/src/App/Views/admin/member/create.php';

?>
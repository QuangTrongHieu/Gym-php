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
    <div>
        <a href="/gym-php/admin/trainer/export" class="btn btn-success me-2">
            <i class="fas fa-file-excel"></i> Xuất Excel
        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> Thêm Huấn luyện viên
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
            <th>Chuyên môn</th>
            <th>Lương</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($trainer)): ?>
            <?php foreach($trainer as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td>
                    <?php
                    $avatar = $item['avatar'] ?? 'default.jpg';
                    $avatarFullPath = ROOT_PATH . '/public/uploads/trainers/' . $avatar;
                    $avatarUrl = file_exists($avatarFullPath) 
                        ? '/gym-php/public/uploads/trainers/' . $avatar 
                        : '/gym-php/public/assets/images/default-avatar.png';
                    ?>
                    <div class="avatar-container">
                        <img src="<?= htmlspecialchars($avatarUrl) ?>" 
                             class="trainer-avatar" 
                             alt="<?= htmlspecialchars($item['fullName']) ?>">
                    </div>
                </td>
                <td><?= htmlspecialchars($item['fullName']) ?></td>
                <td><?= htmlspecialchars($item['username']) ?></td>
                <td><?= htmlspecialchars($item['email']) ?></td>
                <td><?= htmlspecialchars($item['phone']) ?></td>
                <td><?= htmlspecialchars($item['specialization']) ?></td>
                <td><?= number_format($item['salary']) ?> VNĐ</td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $item['id'] ?>">
                        <i class="fas fa-edit"></i> Sửa
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="showDeleteModal(<?= $item['id'] ?>, '<?= htmlspecialchars($item['fullName']) ?>')">
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                </td>
            </tr>

            <!-- Edit Modal for each trainer -->
            <div class="modal fade" id="editModal<?= $item['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Sửa thông tin huấn luyện viên</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/gym-php/admin/trainer/edit/<?= $item['id'] ?>" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label">Ảnh đại diện</label>
                                    <input type="file" name="avatar" class="form-control" accept="image/*">
                                    <?php if (!empty($item['avatar'])): ?>
                                        <div class="mt-2">
                                            <img src="<?= htmlspecialchars($avatarUrl) ?>" 
                                                 alt="Current avatar" 
                                                 class="trainer-avatar"
                                                 style="width: 100px; height: 100px;">
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($item['username']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Họ tên</label>
                                    <input type="text" name="fullName" class="form-control" value="<?= htmlspecialchars($item['fullName']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($item['email']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password (để trống nếu không thay đổi)</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ngày sinh</label>
                                    <input type="date" name="dateOfBirth" class="form-control" value="<?= htmlspecialchars($item['dateOfBirth']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Giới tính</label>
                                    <select name="sex" class="form-control" required>
                                        <option value="Male" <?= $item['sex'] == 'Male' ? 'selected' : '' ?>>Nam</option>
                                        <option value="Female" <?= $item['sex'] == 'Female' ? 'selected' : '' ?>>Nữ</option>
                                        <option value="Other" <?= $item['sex'] == 'Other' ? 'selected' : '' ?>>Khác</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="tel" name="phone" class="form-control" value="<?= htmlspecialchars($item['phone']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Chuyên môn</label>
                                    <textarea name="specialization" class="form-control" required><?= htmlspecialchars($item['specialization']) ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kinh nghiệm (năm)</label>
                                    <input type="number" name="experience" class="form-control" value="<?= htmlspecialchars($item['experience']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Chứng chỉ</label>
                                    <textarea name="certification" class="form-control" required><?= htmlspecialchars($item['certification']) ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Lương</label>
                                    <input type="number" name="salary" class="form-control" value="<?= htmlspecialchars($item['salary']) ?>" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9">Không có dữ liệu</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- Delete Modal -->
<div class="modal fade" id="deleteTrainerModal" tabindex="-1" aria-labelledby="deleteTrainerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteTrainerModalLabel">Xóa Huấn luyện viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Bạn có chắc chắn muốn xóa huấn luyện viên <strong id="deleteTrainerName"></strong> không?
                    <br>
                    <small class="text-muted">Hành động này không thể hoàn tác.</small>
                </div>
                <form id="deleteTrainerForm" method="POST">
                    <input type="hidden" id="deleteTrainerId" name="id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <button type="button" class="btn btn-danger" onclick="submitDeleteForm()">
                    <i class="fas fa-trash-alt"></i> Xác nhận xóa
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showDeleteModal(id, name) {
    document.getElementById('deleteTrainerName').textContent = name;
    document.getElementById('deleteTrainerId').value = id;
    document.getElementById('deleteTrainerForm').action = `/gym-php/admin/trainer/destroy/${id}`;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteTrainerModal'));
    deleteModal.show();
}

function submitDeleteForm() {
    document.getElementById('deleteTrainerForm').submit();
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

.trainer-avatar {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.2s ease-in-out;
}

.avatar-container:hover .trainer-avatar {
    transform: scale(1.1);
}
</style>

<?php 
require_once ROOT_PATH . '/src/App/Views/admin/Trainer/edit.php';
require_once ROOT_PATH . '/src/App/Views/admin/Trainer/create.php';
require_once ROOT_PATH . '/src/App/Views/admin/Trainer/delete.php';
?>
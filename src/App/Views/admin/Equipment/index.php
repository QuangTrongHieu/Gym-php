   <script>
        $(document).ready(function() {
            $('.table').DataTable({
                "pageLength": 10,
                "language": {
                    "lengthMenu": "Hiển thị _MENU_ mục",
                    "zeroRecords": "Không tìm thấy kết quả",
                    "info": "Hiển thị trang _PAGE_ của _PAGES_",
                    "infoEmpty": "Không có dữ liệu",
                    "infoFiltered": "(lọc từ _MAX_ tổng số mục)",
                    "search": "Tìm kiếm:",
                    "paginate": {
                        "first": "Đầu",
                        "last": "Cuối",
                        "next": "Sau",
                        "previous": "Trước"
                    }
                }
            });
        });
    </script>
</head>

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
    <h1>Quản lý Thiết bị</h1>
    <a href="/gym/admin/equipment/create" class="btn btn-primary">Thêm Thiết bị</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Mô tả</th>
            <th>Ngày mua</th>
            <th>Giá</th>
            <th>Trạng thái</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <?php if (isset($equipments) && is_array($equipments)): ?>
        <tbody>
            <?php foreach ($equipments as $equipment): ?>
            <tr>
                <td><?= $equipment['id'] ?></td>
                <td><?= $equipment['name'] ?></td>
                <td><?= $equipment['description'] ?></td>
                <td><?= $equipment['purchaseDate'] ?></td>
                <td><?= number_format($equipment['price']) ?> VNĐ</td>
                <td><?= $equipment['status'] ?></td>
                <td>
                    <button type="button" 
                            class="btn btn-sm btn-warning" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editModal<?= $equipment['id'] ?>">
                        Sửa
                    </button>
                    <button type="button" 
                            class="btn btn-sm btn-danger" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteModal<?= $equipment['id'] ?>">
                        Xóa
                    </button>
                </td>
            </tr>
            <!-- Modal sửa thiết bị -->
            <div class="modal fade" id="editModal<?= $equipment['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="/admin/equipment/edit/<?= $equipment['id'] ?>" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Sửa Thiết bị</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Thêm các trường nhập liệu cho thiết bị -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên</label>
                                    <input type="text" class="form-control" name="name" value="<?= $equipment['name'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea class="form-control" name="description" required><?= $equipment['description'] ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="purchaseDate" class="form-label">Ngày mua</label>
                                    <input type="date" class="form-control" name="purchaseDate" value="<?= $equipment['purchaseDate'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá</label>
                                    <input type="number" class="form-control" name="price" value="<?= $equipment['price'] ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select class="form-select" name="status" required>
                                        <option value="active" <?= $equipment['status'] == 'active' ? 'selected' : '' ?>>Hoạt động</option>
                                        <option value="inactive" <?= $equipment['status'] == 'inactive' ? 'selected' : '' ?>>Ngừng hoạt động</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal xóa thiết bị -->
            <div class="modal fade" id="deleteModal<?= $equipment['id'] ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="/admin/equipment/delete/<?= $equipment['id'] ?>" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Xóa Thiết bị</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Bạn có chắc chắn muốn xóa thiết bị này không?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-danger">Xóa</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </tbody>
    <?php else: ?>
        <tbody>
            <tr>
                <td colspan="7" class="text-center">Không có thiết bị nào để hiển thị.</td>
            </tr>
        </tbody>
    <?php endif; ?>
</table>

<?php 
require_once ROOT_PATH . '/src/App/Views/admin/Equipment/create.php';
require_once ROOT_PATH . '/src/App/Views/admin/Equipment/edit.php';
require_once ROOT_PATH . '/src/App/Views/admin/Equipment/delete.php';
?>

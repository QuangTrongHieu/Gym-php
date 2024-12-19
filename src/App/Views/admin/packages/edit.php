<?php foreach ($Packages as $Package): ?> // Thay đổi từ $Trainers sang $Packages
    <div class="modal fade" id="editModal<?= $Package['id'] ?>" tabindex="-1"> // Thay đổi từ $Trainer sang $Package
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa Thông tin Gói Tập</h5> // Cập nhật tiêu đề
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="/gym/admin/package/edit/<?= $Package['id'] ?>" method="POST"> // Cập nhật đường dẫn
                        <input type="hidden" name="id" value="<?= $Package['id'] ?>">

                        <div class="mb-3">
                            <label class="form-label">Tên Gói</label> // Cập nhật nhãn
                            <input type="text" name="name" class="form-control" 
                                   value="<?= $Package['name'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Giá</label> // Cập nhật nhãn
                            <input type="number" name="price" class="form-control" 
                                   value="<?= $Package['price'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thời gian (tháng)</label> // Cập nhật nhãn
                            <input type="number" name="duration" class="form-control" 
                                   value="<?= $Package['duration'] ?>" required>
                        </div>

                        <div class="modal-footer px-0 pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
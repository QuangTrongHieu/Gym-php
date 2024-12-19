<?php foreach ($Trainers as $Trainer): ?>
    <div class="modal fade" id="deleteModal<?= $Trainer['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận Xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="/gym/admin/Trainer-management/delete/<?= $Trainer['id'] ?>" method="POST">
                        <input type="hidden" name="id" value="<?= $Trainer['id'] ?>">
                        <p>Bạn có chắc muốn xóa nhân viên này?</p>
                        <p><strong>Họ tên:</strong> <?= $Trainer['fullName'] ?></p>
                        <p><strong>Email:</strong> <?= $Trainer['email'] ?></p>
                        
                        <div class="modal-footer px-0 pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Danh sách lịch tập</h6>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                        <i class="fas fa-plus"></i> Thêm lịch tập
                    </button>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success mx-4">
                            <?php 
                                echo $_SESSION['success'];
                                unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger mx-4">
                            <?php 
                                echo $_SESSION['error'];
                                unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hội viên</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Huấn luyện viên</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ngày tập</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Thời gian</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Trạng thái</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ghi chú</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($schedules)): ?>
                                    <?php foreach ($schedules as $schedule): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0"><?= $schedule['id'] ?></p>
                                            </td>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0"><?= $schedule['user_name'] ?></p>
                                            </td>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0"><?= $schedule['trainer_name'] ?></p>
                                            </td>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0"><?= date('d/m/Y', strtotime($schedule['training_date'])) ?></p>
                                            </td>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0">
                                                    <?= date('H:i', strtotime($schedule['start_time'])) ?> - 
                                                    <?= date('H:i', strtotime($schedule['end_time'])) ?>
                                                </p>
                                            </td>
                                            <td class="ps-4">
                                                <span class="badge badge-sm bg-gradient-<?= $schedule['status'] == 'confirmed' ? 'success' : ($schedule['status'] == 'pending' ? 'warning' : 'danger') ?>">
                                                    <?= $schedule['status'] == 'confirmed' ? 'Đã xác nhận' : ($schedule['status'] == 'pending' ? 'Chờ xác nhận' : 'Đã hủy') ?>
                                                </span>
                                            </td>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0"><?= $schedule['notes'] ?></p>
                                            </td>
                                            <td class="ps-4">
                                                <button type="button" class="btn btn-link text-secondary mb-0" 
                                                        onclick="editSchedule(<?= htmlspecialchars(json_encode($schedule)) ?>)">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </button>
                                                <form action="/gym-php/admin/schedule/delete/<?= $schedule['id'] ?>" method="POST" class="d-inline">
                                                    <button type="submit" class="btn btn-link text-danger mb-0" 
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                        <i class="fas fa-trash text-xs"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Schedule Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm lịch tập mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/gym-php/admin/schedule/create" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Hội viên</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">Chọn hội viên</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Huấn luyện viên</label>
                        <select name="trainer_id" class="form-select" required>
                            <option value="">Chọn huấn luyện viên</option>
                            <?php foreach ($trainers as $trainer): ?>
                                <option value="<?= $trainer['id'] ?>"><?= $trainer['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày tập</label>
                        <input type="date" name="training_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giờ bắt đầu</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giờ kết thúc</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select" required>
                            <option value="pending">Chờ xác nhận</option>
                            <option value="confirmed">Đã xác nhận</option>
                            <option value="cancelled">Đã hủy</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Schedule Modal -->
<div class="modal fade" id="editScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa lịch tập</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editScheduleForm" action="" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Hội viên</label>
                        <select name="user_id" id="edit_user_id" class="form-select" required>
                            <option value="">Chọn hội viên</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>"><?= $user['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Huấn luyện viên</label>
                        <select name="trainer_id" id="edit_trainer_id" class="form-select" required>
                            <option value="">Chọn huấn luyện viên</option>
                            <?php foreach ($trainers as $trainer): ?>
                                <option value="<?= $trainer['id'] ?>"><?= $trainer['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày tập</label>
                        <input type="date" name="training_date" id="edit_training_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giờ bắt đầu</label>
                        <input type="time" name="start_time" id="edit_start_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giờ kết thúc</label>
                        <input type="time" name="end_time" id="edit_end_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" id="edit_notes" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" id="edit_status" class="form-select" required>
                            <option value="pending">Chờ xác nhận</option>
                            <option value="confirmed">Đã xác nhận</option>
                            <option value="cancelled">Đã hủy</option>
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


<script>
function editSchedule(schedule) {
    document.getElementById('editScheduleForm').action = `/gym-php/admin/schedule/update/${schedule.id}`;
    document.getElementById('edit_user_id').value = schedule.user_id;
    document.getElementById('edit_trainer_id').value = schedule.trainer_id;
    document.getElementById('edit_training_date').value = schedule.training_date;
    document.getElementById('edit_start_time').value = schedule.start_time;
    document.getElementById('edit_end_time').value = schedule.end_time;
    document.getElementById('edit_notes').value = schedule.notes;
    document.getElementById('edit_status').value = schedule.status;
    
    new bootstrap.Modal(document.getElementById('editScheduleModal')).show();
}
</script>

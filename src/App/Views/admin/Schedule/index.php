
<div class="container-fluid px-4">
    <h1 class="mt-4">Quản lý lịch tập</h1>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php 
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Danh sách lịch tập
            <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                <i class="fas fa-plus"></i> Thêm mới
            </button>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Huấn luyện viên</th>
                        <th>Ngày tập</th>
                        <th>Giờ bắt đầu</th>
                        <th>Giờ kết thúc</th>
                        <th>Ghi chú</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedules as $schedule): ?>
                    <tr>
                        <td><?= $schedule['id'] ?></td>
                        <td><?= $schedule['user_name'] ?></td>
                        <td><?= $schedule['trainer_name'] ?></td>
                        <td><?= date('d/m/Y', strtotime($schedule['training_date'])) ?></td>
                        <td><?= date('H:i', strtotime($schedule['start_time'])) ?></td>
                        <td><?= date('H:i', strtotime($schedule['end_time'])) ?></td>
                        <td><?= $schedule['notes'] ?></td>
                        <td>
                            <?php
                            $statusClass = '';
                            switch($schedule['status']) {
                                case 'pending':
                                    $statusClass = 'bg-warning';
                                    $statusText = 'Chờ xác nhận';
                                    break;
                                case 'confirmed':
                                    $statusClass = 'bg-primary';
                                    $statusText = 'Đã xác nhận';
                                    break;
                                case 'completed':
                                    $statusClass = 'bg-success';
                                    $statusText = 'Hoàn thành';
                                    break;
                                case 'cancelled':
                                    $statusClass = 'bg-danger';
                                    $statusText = 'Đã hủy';
                                    break;
                            }
                            ?>
                            <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editScheduleModal<?= $schedule['id'] ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteScheduleModal<?= $schedule['id'] ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
                        <label class="form-label">Khách hàng</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">Chọn khách hàng</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>"><?= $user['fullName'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Huấn luyện viên</label>
                        <select name="trainer_id" class="form-select" required>
                            <option value="">Chọn huấn luyện viên</option>
                            <?php foreach ($trainers as $trainer): ?>
                                <option value="<?= $trainer['id'] ?>"><?= $trainer['fullName'] ?></option>
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
                            <option value="completed">Hoàn thành</option>
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
<?php foreach ($schedules as $schedule): ?>
<div class="modal fade" id="editScheduleModal<?= $schedule['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa lịch tập</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/gym-php/admin/schedule/update/<?= $schedule['id'] ?>" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Khách hàng</label>
                        <select name="user_id" class="form-select" required>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>" <?= $user['id'] == $schedule['user_id'] ? 'selected' : '' ?>>
                                    <?= $user['fullName'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Huấn luyện viên</label>
                        <select name="trainer_id" class="form-select" required>
                            <?php foreach ($trainers as $trainer): ?>
                                <option value="<?= $trainer['id'] ?>" <?= $trainer['id'] == $schedule['trainer_id'] ? 'selected' : '' ?>>
                                    <?= $trainer['fullName'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày tập</label>
                        <input type="date" name="training_date" class="form-control" value="<?= $schedule['training_date'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giờ bắt đầu</label>
                        <input type="time" name="start_time" class="form-control" value="<?= date('H:i', strtotime($schedule['start_time'])) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giờ kết thúc</label>
                        <input type="time" name="end_time" class="form-control" value="<?= date('H:i', strtotime($schedule['end_time'])) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="3"><?= $schedule['notes'] ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" <?= $schedule['status'] == 'pending' ? 'selected' : '' ?>>Chờ xác nhận</option>
                            <option value="confirmed" <?= $schedule['status'] == 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                            <option value="completed" <?= $schedule['status'] == 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                            <option value="cancelled" <?= $schedule['status'] == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
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

<!-- Delete Schedule Modal -->
<div class="modal fade" id="deleteScheduleModal<?= $schedule['id'] ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa lịch tập này?</p>
                <p><strong>Khách hàng:</strong> <?= $schedule['user_name'] ?></p>
                <p><strong>Huấn luyện viên:</strong> <?= $schedule['trainer_name'] ?></p>
                <p><strong>Ngày tập:</strong> <?= date('d/m/Y', strtotime($schedule['training_date'])) ?></p>
                <p><strong>Thời gian:</strong> <?= date('H:i', strtotime($schedule['start_time'])) ?> - <?= date('H:i', strtotime($schedule['end_time'])) ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form action="/gym-php/admin/schedule/delete/<?= $schedule['id'] ?>" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

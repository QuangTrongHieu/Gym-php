<?php
$currentMonth = $_GET['month'] ?? date('m');
$currentYear = $_GET['year'] ?? date('Y');
$monthName = date('F', strtotime("$currentYear-$currentMonth-01"));
$userRole = $_SESSION['user']['role'] ?? '';
$userId = $_SESSION['user']['id'] ?? '';
$prevMonth = $currentMonth - 1;
$prevYear = $currentYear;
if ($prevMonth < 1) {
    $prevMonth = 12;
    $prevYear--;
}
$nextMonth = $currentMonth + 1;
$nextYear = $currentYear;
if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear++;
}
$firstDayOfMonth = strtotime("$currentYear-$currentMonth-01");
$daysInMonth = date('t', $firstDayOfMonth);
$firstDayOfWeek = date('w', $firstDayOfMonth);
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <!-- Header Section with Filters -->
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-0">Lịch Tập - <?= $monthName ?> <?= $currentYear ?></h5>
                            <div class="btn-group mt-2">
                                <a href="?month=<?= $prevMonth ?>&year=<?= $prevYear ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-chevron-left"></i> Tháng trước
                                </a>
                                <a href="?month=<?= date('m') ?>&year=<?= date('Y') ?>" class="btn btn-sm btn-outline-primary">
                                    Tháng hiện tại
                                </a>
                                <a href="?month=<?= $nextMonth ?>&year=<?= $nextYear ?>" class="btn btn-sm btn-outline-primary">
                                    Tháng sau <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php if (in_array($userRole, ['admin', 'trainer'])): ?>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                            <i class="fas fa-plus"></i> Thêm lịch tập
                        </button>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Filter Controls -->
                    <?php if ($userRole === 'admin'): ?>
                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-auto">
                            <select id="viewType" class="form-select">
                                <option value="calendar">Xem theo lịch</option>
                                <option value="list">Xem theo danh sách</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <select id="filterType" class="form-select">
                                <option value="all">Tất cả</option>
                                <option value="user">Theo hội viên</option>
                                <option value="trainer">Theo huấn luyện viên</option>
                            </select>
                        </div>
                        <div class="col-auto" id="filterUserContainer" style="display: none;">
                            <select id="filterUser" class="form-select">
                                <option value="">Chọn hội viên</option>
                                <?php foreach ($users as $user): ?>
                                    <option value="<?= $user['id'] ?>"><?= $user['fullName'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-auto" id="filterTrainerContainer" style="display: none;">
                            <select id="filterTrainer" class="form-select">
                                <option value="">Chọn huấn luyện viên</option>
                                <?php foreach ($trainers as $trainer): ?>
                                    <option value="<?= $trainer['id'] ?>"><?= $trainer['fullName'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <!-- Calendar View -->
                    <div id="calendarView">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Chủ Nhật</th>
                                        <th>Thứ 2</th>
                                        <th>Thứ 3</th>
                                        <th>Thứ 4</th>
                                        <th>Thứ 5</th>
                                        <th>Thứ 6</th>
                                        <th>Thứ 7</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $day = 1;
                                    for ($i = 0; $i < 6; $i++) {
                                        echo "<tr style='height: 120px;'>";
                                        for ($j = 0; $j < 7; $j++) {
                                            if (($i === 0 && $j < $firstDayOfWeek) || ($day > $daysInMonth)) {
                                                echo "<td class='bg-light'></td>";
                                            } else {
                                                $currentDate = sprintf("%04d-%02d-%02d", $currentYear, $currentMonth, $day);
                                                $isToday = $currentDate === date('Y-m-d');
                                                $daySchedules = array_filter($schedules, function($schedule) use ($currentDate) {
                                                    return $schedule['training_date'] === $currentDate;
                                                });
                                                
                                                echo "<td class='position-relative" . ($isToday ? " bg-light" : "") . "'>";
                                                echo "<div class='date-number" . ($isToday ? " text-primary fw-bold" : "") . "'>$day</div>";
                                                echo "<div class='schedule-container' style='max-height: 100px; overflow-y: auto;'>";
                                                foreach ($daySchedules as $schedule) {
                                                    $statusClass = $schedule['status'] === 'confirmed' ? 'success' : 
                                                                ($schedule['status'] === 'pending' ? 'warning' : 'danger');
                                                    $canEdit = $userRole === 'admin' || 
                                                            ($userRole === 'trainer' && $schedule['trainer_id'] == $userId);
                                                    
                                                    echo "<div class='schedule-item bg-{$statusClass} p-1 mb-1 rounded text-white' 
                                                               style='font-size: 0.8em; " . ($canEdit ? "cursor: pointer;" : "") . "'
                                                               " . ($canEdit ? "onclick='editSchedule(" . json_encode($schedule) . ")'" : "") . ">";
                                                    echo "<div class='time'>" . date('H:i', strtotime($schedule['start_time'])) . "</div>";
                                                    echo "<div class='user'>" . $schedule['user_name'] . "</div>";
                                                    echo "</div>";
                                                }
                                                echo "</div></td>";
                                                $day++;
                                            }
                                        }
                                        echo "</tr>";
                                        if ($day > $daysInMonth) break;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- List View -->
                    <div id="listView" style="display: none;">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Ngày</th>
                                        <th>Thời gian</th>
                                        <th>Hội viên</th>
                                        <th>Huấn luyện viên</th>
                                        <th>Trạng thái</th>
                                        <th>Ghi chú</th>
                                        <?php if (in_array($userRole, ['admin', 'trainer'])): ?>
                                        <th>Thao tác</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($schedules as $schedule): 
                                        $canEdit = $userRole === 'admin' || 
                                                ($userRole === 'trainer' && $schedule['trainer_id'] == $userId);
                                    ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($schedule['training_date'])) ?></td>
                                            <td>
                                                <?= date('H:i', strtotime($schedule['start_time'])) ?> - 
                                                <?= date('H:i', strtotime($schedule['end_time'])) ?>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark font-weight-bold"><?= $schedule['user_name'] ?></span>
                                                    <small class="text-muted">
                                                        <i class="fas fa-envelope me-1"></i><?= $schedule['user_email'] ?><br>
                                                        <i class="fas fa-phone me-1"></i><?= $schedule['user_phone'] ?>
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark font-weight-bold"><?= $schedule['trainer_name'] ?></span>
                                                    <small class="text-muted">
                                                        <i class="fas fa-envelope me-1"></i><?= $schedule['trainer_email'] ?><br>
                                                        <i class="fas fa-phone me-1"></i><?= $schedule['trainer_phone'] ?>
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $schedule['status'] === 'confirmed' ? 'success' : 
                                                    ($schedule['status'] === 'pending' ? 'warning' : 'danger') ?>">
                                                    <?= $schedule['status'] === 'confirmed' ? 'Đã xác nhận' : 
                                                        ($schedule['status'] === 'pending' ? 'Chờ xác nhận' : 'Đã hủy') ?>
                                                </span>
                                            </td>
                                            <td><?= $schedule['notes'] ?></td>
                                            <?php if (in_array($userRole, ['admin', 'trainer'])): ?>
                                            <td>
                                                <?php if ($canEdit): ?>
                                                <button type="button" class="btn btn-link text-primary mb-0" 
                                                        onclick='editSchedule(<?= json_encode($schedule) ?>)'>
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <?php endif; ?>
                                            </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Schedule Modal -->
<?php if (in_array($userRole, ['admin', 'trainer'])): ?>
<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm lịch tập</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/gym-php/admin/schedule/create" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Hội viên</label>
                        <select name="user_id" class="form-select" required>
                            <option value="">Chọn hội viên</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>"><?= $user['fullName'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Huấn luyện viên</label>
                        <select name="trainer_id" class="form-select" required>
                            <?php if ($userRole === 'trainer'): ?>
                                <option value="<?= $userId ?>"><?= $_SESSION['user']['fullName'] ?></option>
                            <?php else: ?>
                                <option value="">Chọn huấn luyện viên</option>
                                <?php foreach ($trainers as $trainer): ?>
                                    <option value="<?= $trainer['id'] ?>"><?= $trainer['fullName'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày tập</label>
                        <input type="date" name="training_date" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giờ bắt đầu</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giờ kết thúc</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>
                    <?php if ($userRole === 'admin'): ?>
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="pending">Chờ xác nhận</option>
                            <option value="confirmed">Đã xác nhận</option>
                            <option value="cancelled">Đã hủy</option>
                        </select>
                    </div>
                    <?php else: ?>
                        <input type="hidden" name="status" value="pending">
                    <?php endif; ?>
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
            <form id="editScheduleForm" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Hội viên</label>
                        <select name="user_id" id="edit_user_id" class="form-select" required>
                            <option value="">Chọn hội viên</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>"><?= $user['fullName'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Huấn luyện viên</label>
                        <select name="trainer_id" id="edit_trainer_id" class="form-select" required>
                            <?php if ($userRole === 'trainer'): ?>
                                <option value="<?= $userId ?>"><?= $_SESSION['user']['fullName'] ?></option>
                            <?php else: ?>
                                <option value="">Chọn huấn luyện viên</option>
                                <?php foreach ($trainers as $trainer): ?>
                                    <option value="<?= $trainer['id'] ?>"><?= $trainer['fullName'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày tập</label>
                        <input type="date" name="training_date" id="edit_training_date" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giờ bắt đầu</label>
                            <input type="time" name="start_time" id="edit_start_time" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giờ kết thúc</label>
                            <input type="time" name="end_time" id="edit_end_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" id="edit_notes" class="form-control" rows="3"></textarea>
                    </div>
                    <?php if ($userRole === 'admin'): ?>
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" id="edit_status" class="form-select">
                            <option value="pending">Chờ xác nhận</option>
                            <option value="confirmed">Đã xác nhận</option>
                            <option value="cancelled">Đã hủy</option>
                        </select>
                    </div>
                    <?php else: ?>
                        <input type="hidden" name="status" id="edit_status" value="pending">
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
.date-number {
    position: absolute;
    top: 5px;
    left: 5px;
    font-size: 0.9em;
    color: #666;
}

.schedule-container {
    margin-top: 25px;
    padding: 2px;
}

.schedule-item {
    margin-bottom: 2px;
    padding: 3px;
    border-radius: 3px;
}

.schedule-item .time {
    font-weight: bold;
}

.schedule-item .user {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View type toggle
    const viewType = document.getElementById('viewType');
    const calendarView = document.getElementById('calendarView');
    const listView = document.getElementById('listView');
    
    viewType?.addEventListener('change', function() {
        if (this.value === 'calendar') {
            calendarView.style.display = 'block';
            listView.style.display = 'none';
        } else {
            calendarView.style.display = 'none';
            listView.style.display = 'block';
        }
    });

    // Filter type toggle
    const filterType = document.getElementById('filterType');
    const filterUserContainer = document.getElementById('filterUserContainer');
    const filterTrainerContainer = document.getElementById('filterTrainerContainer');
    const filterUser = document.getElementById('filterUser');
    const filterTrainer = document.getElementById('filterTrainer');
    
    filterType?.addEventListener('change', function() {
        if (this.value === 'user') {
            filterUserContainer.style.display = 'block';
            filterTrainerContainer.style.display = 'none';
            filterTrainer.value = '';
        } else if (this.value === 'trainer') {
            filterUserContainer.style.display = 'none';
            filterTrainerContainer.style.display = 'block';
            filterUser.value = '';
        } else {
            filterUserContainer.style.display = 'none';
            filterTrainerContainer.style.display = 'none';
            filterUser.value = '';
            filterTrainer.value = '';
        }
        updateFilters();
    });

    filterUser?.addEventListener('change', updateFilters);
    filterTrainer?.addEventListener('change', updateFilters);

    function updateFilters() {
        const type = filterType.value;
        const id = type === 'user' ? filterUser.value : 
                  type === 'trainer' ? filterTrainer.value : '';
        
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('filter_type', type);
        if (id) {
            currentUrl.searchParams.set('filter_id', id);
        } else {
            currentUrl.searchParams.delete('filter_id');
        }
        
        window.location.href = currentUrl.toString();
    }
});

function editSchedule(schedule) {
    const modal = new bootstrap.Modal(document.getElementById('editScheduleModal'));
    
    document.getElementById('editScheduleForm').action = `/gym-php/admin/schedule/update/${schedule.id}`;
    document.getElementById('edit_user_id').value = schedule.user_id;
    document.getElementById('edit_trainer_id').value = schedule.trainer_id;
    document.getElementById('edit_training_date').value = schedule.training_date;
    document.getElementById('edit_start_time').value = schedule.start_time;
    document.getElementById('edit_end_time').value = schedule.end_time;
    document.getElementById('edit_notes').value = schedule.notes;
    document.getElementById('edit_status').value = schedule.status;
    
    modal.show();
}
</script>
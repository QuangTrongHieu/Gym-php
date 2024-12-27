<?php

namespace App\Controllers;

use App\Models\Schedule;
use App\Models\User;
use App\Models\Trainer;
use Core\View;

class ScheduleController extends BaseController
{
    private $scheduleModel;
    private $userModel;
    private $trainerModel;

    public function __construct()
    {
        parent::__construct();
        $this->scheduleModel = new Schedule();
        $this->userModel = new User();
        $this->trainerModel = new Trainer();
    }

    public function index()
    {
        // Kiểm tra quyền truy cập
        $userRole = $_SESSION['user']['role'] ?? '';
        $userId = $_SESSION['user']['id'] ?? null;
        
        if (!$userId) {
            header('Location: /gym-php/login');
            exit();
        }

        $currentMonth = $_GET['month'] ?? date('m');
        $currentYear = $_GET['year'] ?? date('Y');
        $filter_type = $_GET['filter_type'] ?? 'all';
        $filter_id = $_GET['filter_id'] ?? null;

        // Lấy danh sách lịch tập dựa trên vai trò và bộ lọc
        switch ($userRole) {
            case 'admin':
                if ($filter_type === 'user' && $filter_id) {
                    $schedules = $this->scheduleModel->getSchedulesByUser($filter_id, $currentMonth, $currentYear);
                } elseif ($filter_type === 'trainer' && $filter_id) {
                    $schedules = $this->scheduleModel->getSchedulesByTrainer($filter_id, $currentMonth, $currentYear);
                } else {
                    $schedules = $this->scheduleModel->getAllSchedulesWithNames($currentMonth, $currentYear);
                }
                break;
                
            case 'trainer':
                $schedules = $this->scheduleModel->getSchedulesByTrainer($userId, $currentMonth, $currentYear);
                break;
                
            case 'user':
                $schedules = $this->scheduleModel->getSchedulesByUser($userId, $currentMonth, $currentYear);
                break;
                
            default:
                header('Location: /gym-php/login');
                exit();
        }

        // Lấy danh sách users và trainers cho form thêm/sửa
        $users = $userRole === 'admin' ? $this->userModel->getAllUsers() : [];
        $trainers = $userRole === 'admin' ? $this->trainerModel->getAllTrainers() : [];

        // Chuẩn bị dữ liệu cho calendar
        $firstDayOfMonth = strtotime("$currentYear-$currentMonth-01");
        $daysInMonth = date('t', $firstDayOfMonth);
        $firstDayOfWeek = date('w', $firstDayOfMonth);
        
        // Lấy tháng trước và tháng sau
        $prevMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
        $prevYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;
        $nextMonth = $currentMonth == 12 ? 1 : $currentMonth + 1;
        $nextYear = $currentMonth == 12 ? $currentYear + 1 : $currentYear;

        $this->view('admin/Schedule/index', [
            'title' => 'Quản lý Lịch Tập',
            'schedules' => $schedules,
            'users' => $users,
            'trainers' => $trainers,
            'filter_type' => $filter_type,
            'filter_id' => $filter_id,
            'userRole' => $userRole,
            'userId' => $userId,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'daysInMonth' => $daysInMonth,
            'firstDayOfWeek' => $firstDayOfWeek,
            'prevMonth' => $prevMonth,
            'prevYear' => $prevYear,
            'nextMonth' => $nextMonth,
            'nextYear' => $nextYear
        ]);
    }

    public function create()
    {
        $userRole = $_SESSION['user']['role'] ?? '';
        $userId = $_SESSION['user']['id'] ?? null;
        
        if (!$userId || !in_array($userRole, ['admin', 'trainer'])) {
            $this->json(['error' => 'Không có quyền truy cập'], 403);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate input data
            if (empty($_POST['user_id']) || empty($_POST['trainer_id']) || 
                empty($_POST['training_date']) || empty($_POST['start_time']) || 
                empty($_POST['end_time'])) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
                header('Location: /gym-php/admin/schedule');
                exit();
            }

            // Kiểm tra xung đột lịch
            $isConflict = $this->scheduleModel->checkScheduleConflict(
                $_POST['trainer_id'],
                $_POST['training_date'],
                $_POST['start_time'],
                $_POST['end_time']
            );

            if ($isConflict) {
                $_SESSION['error'] = 'Huấn luyện viên đã có lịch tập trong thời gian này';
                header('Location: /gym-php/admin/schedule');
                exit();
            }

            $data = [
                'user_id' => $_POST['user_id'],
                'trainer_id' => $_POST['trainer_id'],
                'training_date' => $_POST['training_date'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time'],
                'notes' => $_POST['notes'] ?? '',
                'status' => $_POST['status'] ?? 'pending'
            ];

            if ($this->scheduleModel->create($data)) {
                $_SESSION['success'] = 'Thêm lịch tập thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi thêm lịch tập';
            }

            header('Location: /gym-php/admin/schedule');
            exit();
        }
    }

    public function update($id)
    {
        $userRole = $_SESSION['user']['role'] ?? '';
        $userId = $_SESSION['user']['id'] ?? null;
        
        if (!$userId || !in_array($userRole, ['admin', 'trainer'])) {
            $this->json(['error' => 'Không có quyền truy cập'], 403);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate input data
            if (empty($_POST['user_id']) || empty($_POST['trainer_id']) || 
                empty($_POST['training_date']) || empty($_POST['start_time']) || 
                empty($_POST['end_time'])) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
                header('Location: /gym-php/admin/schedule');
                exit();
            }

            // Kiểm tra xung đột lịch (ngoại trừ lịch hiện tại)
            $isConflict = $this->scheduleModel->checkScheduleConflict(
                $_POST['trainer_id'],
                $_POST['training_date'],
                $_POST['start_time'],
                $_POST['end_time'],
                $id
            );

            if ($isConflict) {
                $_SESSION['error'] = 'Huấn luyện viên đã có lịch tập trong thời gian này';
                header('Location: /gym-php/admin/schedule');
                exit();
            }

            $data = [
                'user_id' => $_POST['user_id'],
                'trainer_id' => $_POST['trainer_id'],
                'training_date' => $_POST['training_date'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time'],
                'notes' => $_POST['notes'] ?? '',
                'status' => $_POST['status']
            ];

            if ($this->scheduleModel->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật lịch tập thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật lịch tập';
            }

            header('Location: /gym-php/admin/schedule');
            exit();
        }
    }

    public function delete($id)
    {
        $userRole = $_SESSION['user']['role'] ?? '';
        $userId = $_SESSION['user']['id'] ?? null;
        
        if (!$userId || !in_array($userRole, ['admin', 'trainer'])) {
            $this->json(['error' => 'Không có quyền truy cập'], 403);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->scheduleModel->delete($id)) {
                $_SESSION['success'] = 'Xóa lịch tập thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi xóa lịch tập';
            }

            header('Location: /gym-php/admin/schedule');
            exit();
        }
    }
}

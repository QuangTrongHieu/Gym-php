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
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /gym-php/login');
            exit();
        }

        // Lấy các tham số từ URL
        $currentMonth = $_GET['month'] ?? date('m');
        $currentYear = $_GET['year'] ?? date('Y');
        $filter_type = $_GET['filter_type'] ?? 'all';
        $filter_id = $_GET['filter_id'] ?? null;

        // Lấy danh sách lịch tập dựa trên bộ lọc
        if ($filter_type === 'user' && $filter_id) {
            $schedules = $this->scheduleModel->getSchedulesByUser($filter_id, $currentMonth, $currentYear);
        } elseif ($filter_type === 'trainer' && $filter_id) {
            $schedules = $this->scheduleModel->getSchedulesByTrainer($filter_id, $currentMonth, $currentYear);
        } else {
            $schedules = $this->scheduleModel->getAllSchedulesWithNames($currentMonth, $currentYear);
        }

        // Lấy danh sách users và trainers cho bộ lọc
        $users = $this->userModel->getAllUsers();
        $trainers = $this->trainerModel->getAllTrainers();

        // Render view với layout admin
        $this->view('admin/Schedule/index', [
            'schedules' => $schedules ?? [],
            'users' => $users ?? [],
            'trainers' => $trainers ?? [],
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'filter_type' => $filter_type,
            'filter_id' => $filter_id,
            'year' => $currentYear,
            'title' => 'Quản lý lịch tập'
        ], 'admin');
    }

    public function scheduleManagement()
    {
        // Lấy các tham số từ URL
        $currentMonth = $_GET['month'] ?? date('m');
        $currentYear = $_GET['year'] ?? date('Y');
        $filter_type = $_GET['filter_type'] ?? 'all';
        $filter_id = $_GET['filter_id'] ?? null;

        // Lấy danh sách lịch tập dựa trên bộ lọc
        if ($filter_type === 'user' && $filter_id) {
            $schedules = $this->scheduleModel->getSchedulesByUser($filter_id, $currentMonth, $currentYear);
        } elseif ($filter_type === 'trainer' && $filter_id) {
            $schedules = $this->scheduleModel->getSchedulesByTrainer($filter_id, $currentMonth, $currentYear);
        } else {
            $schedules = $this->scheduleModel->getAllSchedulesWithNames($currentMonth, $currentYear);
        }

        // Lấy danh sách users và trainers cho bộ lọc
        $users = $this->userModel->getAllUsers();
        $trainers = $this->trainerModel->getAllTrainers();

        // Render view với layout admin
        $this->view('admin/Schedule/index', [
            'schedules' => $schedules ?? [],
            'users' => $users ?? [],
            'trainers' => $trainers ?? [],
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
            'filter_type' => $filter_type,
            'filter_id' => $filter_id,
            'year' => $currentYear
        ], 'admin');
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
            if (
                empty($_POST['user_id']) || empty($_POST['trainer_id']) ||
                empty($_POST['training_date']) || empty($_POST['start_time']) ||
                empty($_POST['end_time'])
            ) {
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
            if (
                empty($_POST['user_id']) || empty($_POST['trainer_id']) ||
                empty($_POST['training_date']) || empty($_POST['start_time']) ||
                empty($_POST['end_time'])
            ) {
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

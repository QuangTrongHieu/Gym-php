<?php

namespace App\Controllers;

use App\Models\Schedule;
use App\Models\User;
use App\Models\Trainer;
use Core\View;

class ScheduleController extends BaseController
{
    public function index()
    {
        // Kiểm tra quyền admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /gym-php/login');
            exit();
        }

        $schedules = $this->scheduleModel->getAllSchedulesWithNames();
        $users = $this->userModel->getAllUsers();
        $trainers = $this->trainerModel->getAllTrainers();

        $this->view('admin/Schedule/index', [
            'title' => 'Quản lý Lịch Tập',
            'schedules' => $schedules,
            'users' => $users,
            'trainers' => $trainers
        ]);
    }

    public function create()
    {
        // Kiểm tra quyền admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /gym-php/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'user_id' => $_POST['user_id'],
                'trainer_id' => $_POST['trainer_id'],
                'training_date' => $_POST['training_date'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time'],
                'notes' => $_POST['notes'] ?? '',
                'status' => $_POST['status']
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
        // Kiểm tra quyền admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /gym-php/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        // Kiểm tra quyền admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /gym-php/login');
            exit();
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

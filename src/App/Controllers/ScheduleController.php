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

    public function __construct($route_params = [])
    {
        parent::__construct($route_params);
        $this->scheduleModel = new Schedule();
        $this->userModel = new User();
        $this->trainerModel = new Trainer();
    }

    public function indexAction()
    {
        $schedules = $this->scheduleModel->getAllSchedulesWithNames();
        $users = $this->userModel->getAllUsers();
        $trainers = $this->trainerModel->getAllTrainers();

        View::render('admin/Schedule/index.php', [
            'schedules' => $schedules,
            'users' => $users,
            'trainers' => $trainers
        ]);
    }

    public function createAction()
    {
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

    public function updateAction()
    {
        $id = $this->route_params['id'];
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

    public function deleteAction()
    {
        $id = $this->route_params['id'];
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

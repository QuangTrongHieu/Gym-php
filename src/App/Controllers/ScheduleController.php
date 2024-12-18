<?php

// Kết nối đến model Schedule
require_once 'models/Schedule.php';
use App\Controllers\BaseController;
use App\Models\Schedule;

class ScheduleController extends BaseController
{
    private $scheduleModel;

    public function __construct()
    {
        $this->scheduleModel = new Schedule();
    }

    public function index()
    {
        $schedules = $this->scheduleModel->findAll();
        $this->view('schedule/index', [
            'title' => 'Quản lý Lịch tập',
            'schedules' => $schedules
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate dữ liệu
            $data = [
                'trainerId' => trim($_POST['trainerId']),
                'dayOfWeek' => trim($_POST['dayOfWeek']),
                'startTime' => trim($_POST['startTime']),
                'endTime' => trim($_POST['endTime']),
                'status' => trim($_POST['status'])
            ];

            // Kiểm tra dữ liệu không được trống
            if (empty($data['trainerId']) || empty($data['dayOfWeek']) || 
                empty($data['startTime']) || empty($data['endTime'])) {
                $message = 'Vui lòng điền đầy đủ thông tin';
                $alertClass = 'alert alert-danger';
                $this->view('schedule/create', [
                    'title' => 'Thêm Lịch Tập Mới',
                    'message' => $message,
                    'alertClass' => $alertClass
                ]);
                return;
            }

            if ($this->scheduleModel->create($data)) {
                $message = 'Thêm lịch tập thành công';
                $alertClass = 'alert alert-success';
                $this->view('schedule/index', [
                    'title' => 'Quản lý Lịch tập',
                    'schedules' => $this->scheduleModel->findAll(),
                    'message' => $message,
                    'alertClass' => $alertClass
                ]);
            } else {
                $message = 'Có lỗi xảy ra, vui lòng thử lại';
                $alertClass = 'alert alert-danger';
                $this->view('schedule/create', [
                    'title' => 'Thêm Lịch Tập Mới',
                    'message' => $message,
                    'alertClass' => $alertClass
                ]);
            }
        } else {
            $this->view('schedule/create', [
                'title' => 'Thêm Lịch Tập Mới'
            ]);
        }
    }
}

// Tương tự, bạn có thể chuyển đổi các hàm `edit` và `delete` theo cách này.
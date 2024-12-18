<?php
namespace App\Controllers;

use App\Models\Trainer;
use App\Models\Schedule;
use App\Models\PTRegistration;
use App\Models\TrainingSession;

class TrainerController {
    private $trainerModel;
    private $scheduleModel; 
    private $ptRegistrationModel;
    private $trainingSessionModel;

    public function __construct() {
        $this->trainerModel = new Trainer();
        $this->scheduleModel = new Schedule();
        $this->ptRegistrationModel = new PTRegistration();
        $this->trainingSessionModel = new TrainingSession();
    }

    // Lấy danh sách huấn luyện viên
    public function index() {
        $trainers = $this->trainerModel->getAllTrainers();
        return [
            'status' => 'success',
            'data' => $trainers
        ];
    }

    // Thêm huấn luyện viên mới
    public function create($data) {
        $required = ['username', 'fullName', 'dateOfBirth', 'sex', 'phone', 
                    'email', 'specialization', 'experience', 'certification', 'salary', 'password'];
        
        foreach($required as $field) {
            if(!isset($data[$field])) {
                return [
                    'status' => 'error',
                    'message' => "Thiếu trường $field"
                ];
            }
        }

        $trainerId = $this->trainerModel->create($data);
        return [
            'status' => 'success',
            'message' => 'Thêm huấn luyện viên thành công',
            'data' => ['id' => $trainerId]
        ];
    }

    // Cập nhật thông tin huấn luyện viên
    public function update($id, $data) {
        $trainer = $this->trainerModel->getById($id);
        if(!$trainer) {
            return [
                'status' => 'error',
                'message' => 'Không tìm thấy huấn luyện viên'
            ];
        }

        $this->trainerModel->update($id, $data);
        return [
            'status' => 'success',
            'message' => 'Cập nhật thành công'
        ];
    }

    // Xóa huấn luyện viên
    public function delete($id) {
        $trainer = $this->trainerModel->getById($id);
        if(!$trainer) {
            return [
                'status' => 'error', 
                'message' => 'Không tìm thấy huấn luyện viên'
            ];
        }

        $this->trainerModel->delete($id);
        return [
            'status' => 'success',
            'message' => 'Xóa huấn luyện viên thành công'
        ];
    }

    // Quản lý lịch làm việc
    public function getSchedule($trainerId) {
        $schedule = $this->scheduleModel->getByTrainerId($trainerId);
        return [
            'status' => 'success',
            'data' => $schedule
        ];
    }

    public function updateSchedule($trainerId, $scheduleData) {
        $this->scheduleModel->updateTrainerSchedule($trainerId, $scheduleData);
        return [
            'status' => 'success',
            'message' => 'Cập nhật lịch làm việc thành công'
        ];
    }

    // Quản lý buổi tập
    public function getTrainingSessions($trainerId) {
        $sessions = $this->trainingSessionModel->getByTrainerId($trainerId);
        return [
            'status' => 'success',
            'data' => $sessions
        ];
    }

    public function updateSessionStatus($sessionId, $status, $notes = '') {
        $this->trainingSessionModel->updateStatus($sessionId, $status, $notes);
        return [
            'status' => 'success',
            'message' => 'Cập nhật trạng thái buổi tập thành công'
        ];
    }

    // Xem thống kê hiệu suất
    public function getPerformanceStats($trainerId) {
        $stats = $this->trainerModel->getPerformanceStats($trainerId);
        return [
            'status' => 'success',
            'data' => $stats
        ];
    }

    // Xem danh sách học viên
    public function getClients($trainerId) {
        $clients = $this->ptRegistrationModel->getClientsByTrainerId($trainerId);
        return [
            'status' => 'success',
            'data' => $clients
        ];
    }

    // Hiển thị trang danh sách huấn luyện viên
    public function showTrainerList() {
        $trainers = $this->trainerModel->getAllTrainers();
        
        // Render view với dữ liệu trainers
        require_once ROOT_PATH . '/src/App/Views/RegisTrainer/RegisTrainer.php';
    }

    // Hiển thị chi tiết huấn luyện viên
    public function showTrainerDetail($id) {
        $trainer = $this->trainerModel->getById($id);
        if (!$trainer) {
            header('Location: /gym-php/trainers');
            exit;
        }
        
        // Lấy thêm thông tin lịch và các buổi tập
        $schedule = $this->scheduleModel->getByTrainerId($id);
        $sessions = $this->trainingSessionModel->getByTrainerId($id);
        
        require_once ROOT_PATH . '/src/App/Views/RegisTrainer/TrainerDetail.php';
    }
}
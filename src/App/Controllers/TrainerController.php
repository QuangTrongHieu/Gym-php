<?php

namespace App\Controllers;

use App\Models\Trainer;
use Core\Helpers\FileUploader;

class TrainerController extends BaseController
{
    private $trainerModel;
    private $uploader;
    private const UPLOAD_DIR = 'public/uploads/trainers';
    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
    private const ALLOWED_TYPES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->trainerModel = new Trainer();
        $this->uploader = new FileUploader();
        
        // Ensure upload directory exists
        $uploadPath = ROOT_PATH . '/' . self::UPLOAD_DIR;
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
    }

    private function handleImageUpload($file, $oldAvatar = null) {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                return false; // No file uploaded, not an error
            }
            throw new \Exception($this->getFileErrorMessage($file['error']));
        }

        // Validate file size
        if ($file['size'] > self::MAX_FILE_SIZE) {
            throw new \Exception('Kích thước file quá lớn. Giới hạn ' . (self::MAX_FILE_SIZE / 1024 / 1024) . 'MB');
        }

        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!array_key_exists($mimeType, self::ALLOWED_TYPES)) {
            throw new \Exception('Loại file không được hỗ trợ. Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WEBP)');
        }

        $uploadDir = ROOT_PATH . '/' . self::UPLOAD_DIR . '/';

        // Delete old avatar if exists
        if ($oldAvatar && $oldAvatar !== 'default.jpg') {
            $oldAvatarPath = $uploadDir . $oldAvatar;
            if (file_exists($oldAvatarPath) && is_file($oldAvatarPath)) {
                unlink($oldAvatarPath);
            }
        }

        // Generate safe filename
        $extension = self::ALLOWED_TYPES[$mimeType];
        $fileName = 'trainer_' . uniqid() . '_' . time() . '.' . $extension;
        $targetPath = $uploadDir . $fileName;

        // Validate image dimensions and content
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            throw new \Exception('File không phải là hình ảnh hợp lệ');
        }

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new \Exception('Không thể lưu file. Vui lòng kiểm tra quyền thư mục');
        }

        // Set secure permissions
        chmod($targetPath, 0644);

        return $fileName;
    }

    private function getFileErrorMessage($error) {
        switch ($error) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'File vượt quá kích thước cho phép (' . (self::MAX_FILE_SIZE / 1024 / 1024) . 'MB)';
            case UPLOAD_ERR_PARTIAL:
                return 'File chỉ được tải lên một phần. Vui lòng thử lại';
            case UPLOAD_ERR_NO_FILE:
                return 'Không có file nào được tải lên';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Thiếu thư mục tạm. Vui lòng liên hệ quản trị viên';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Không thể ghi file. Vui lòng kiểm tra quyền thư mục';
            case UPLOAD_ERR_EXTENSION:
                return 'Upload bị chặn bởi extension';
            default:
                return 'Lỗi không xác định (' . $error . ')';
        }
    }

    public function index()
    {
        $trainer = $this->trainerModel->getAllTrainers();
        if (empty($trainer)) {
            $_SESSION['error'] = 'Không có huấn luyện viên nào được tìm thấy.';
        }
        $this->view('admin/trainer/index', [
            'title' => 'Quản lý huấn luyện viên',
            'trainer' => $trainer
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'],
                'fullName' => $_POST['fullName'],
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'dateOfBirth' => $_POST['dateOfBirth'],
                'sex' => $_POST['sex'],
                'phone' => $_POST['phone'],
                'specialization' => $_POST['specialization'],
                'experience' => $_POST['experience'],
                'certification' => $_POST['certification'],
                'salary' => $_POST['salary'],
                'eRole' => 'TRAINER',
                'status' => 'ACTIVE',
                'avatar' => 'default.jpg'
            ];
            
            try {
                if ($this->trainerModel->create($data)) {
                    $_SESSION['success'] = 'Thêm huấn luyện viên thành công';
                    header('Location: /gym-php/admin/trainer');
                    exit;
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi thêm huấn luyện viên';
                    header('Location: /gym-php/admin/trainer');
                    exit;
                }
            } catch (\Exception $e) {
                $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
                header('Location: /gym-php/admin/trainer');
                exit;
            }
        }
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate trainer exists
                $trainer = $this->trainerModel->getTrainerById($id);
                if (!$trainer) {
                    throw new \Exception('Không tìm thấy huấn luyện viên');
                }

                $data = [
                    'username' => $_POST['username'],
                    'fullName' => $_POST['fullName'],
                    'email' => $_POST['email'],
                    'dateOfBirth' => $_POST['dateOfBirth'],
                    'sex' => $_POST['sex'],
                    'phone' => $_POST['phone'],
                    'specialization' => $_POST['specialization'],
                    'experience' => $_POST['experience'],
                    'certification' => $_POST['certification'],
                    'salary' => $_POST['salary']
                ];

                // Handle password update
                if (!empty($_POST['password'])) {
                    $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }

                // Handle avatar upload
                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $fileName = $this->handleImageUpload($_FILES['avatar'], $trainer['avatar'] ?? null);
                    if ($fileName) {
                        $data['avatar'] = $fileName;
                    }
                }

                // Start transaction
                $this->trainerModel->beginTransaction();

                if (!$this->trainerModel->update($id, $data)) {
                    throw new \Exception('Không thể cập nhật thông tin huấn luyện viên');
                }

                $this->trainerModel->commit();
                $_SESSION['success'] = 'Cập nhật thông tin huấn luyện viên thành công';

            } catch (\Exception $e) {
                if ($this->trainerModel->inTransaction()) {
                    $this->trainerModel->rollBack();
                }
                $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            }

            header('Location: /gym-php/admin/trainer');
            exit;
        }
        
        header('Location: /gym-php/admin/trainer');
        exit;
    }

    public function delete($id)
    {
        try {
            if (empty($id)) {
                throw new \Exception('ID huấn luyện viên không hợp lệ');
            }

            // Fetch trainer with validation
            $trainer = $this->trainerModel->findById($id);
            if (!$trainer) {
                throw new \Exception('Không tìm thấy huấn luyện viên');
            }

            // Check if trainer has active assignments or schedules
            $hasActiveSchedules = $this->trainerModel->hasActiveSchedules($id);
            if ($hasActiveSchedules) {
                throw new \Exception('Không thể xóa huấn luyện viên đang có lịch huấn luyện');
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                return $this->destroy($id);
            }

            $this->view('admin/Trainer/delete', [
                'title' => 'Xóa huấn luyện viên',
                'trainer' => $trainer
            ]);
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            $this->redirect('admin/trainer');
        }
    }

    public function destroy($id)
    {
        try {
            if (empty($id)) {
                throw new \Exception('ID huấn luyện viên không hợp lệ');
            }

            // Get trainer info before deletion for file cleanup
            $trainer = $this->trainerModel->findById($id);
            if (!$trainer) {
                throw new \Exception('Không tìm thấy huấn luyện viên');
            }

            // Start transaction if available
            if (method_exists($this->trainerModel->db, 'beginTransaction')) {
                $this->trainerModel->db->beginTransaction();
            }

            // Delete trainer's avatar if it exists and is not the default
            if (!empty($trainer['avatar']) && $trainer['avatar'] !== 'default.jpg') {
                $avatarPath = ROOT_PATH . '/public/uploads/trainers/' . $trainer['avatar'];
                if (file_exists($avatarPath)) {
                    unlink($avatarPath);
                }
            }

            // Delete trainer record
            if (!$this->trainerModel->delete($id)) {
                throw new \Exception('Không thể xóa thông tin huấn luyện viên');
            }

            // Commit transaction if available
            if (method_exists($this->trainerModel->db, 'commit')) {
                $this->trainerModel->db->commit();
            }

            $_SESSION['success'] = 'Xóa huấn luyện viên thành công';

        } catch (\Exception $e) {
            // Rollback transaction if available
            if (method_exists($this->trainerModel->db, 'rollBack')) {
                $this->trainerModel->db->rollBack();
            }
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }

        $this->redirect('admin/trainer');
    }

    public function list()
    {
        $trainers = $this->trainerModel->findActiveTrainers();
        $this->view('RegisTrainer/list-trainers', [
            'title' => 'Đội ngũ Huấn luyện viên',
            'trainers' => $trainers
        ]);
    }

    public function editProfile()
    {
        // Get current trainer's information
        $trainerId = $_SESSION['trainer_id'];
        $trainer = $this->trainerModel->getTrainerById($trainerId);
        
        if (!$trainer) {
            $_SESSION['error'] = 'Không tìm thấy thông tin huấn luyện viên.';
            $this->redirect('trainer/dashboard');
        }

        $this->view('Trainer/Profile/edit', [
            'title' => 'Chỉnh sửa thông tin cá nhân',
            'trainer' => $trainer
        ]);
    }

    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('trainer/profile/edit');
        }

        $trainerId = $_SESSION['trainer_id'];
        $trainer = $this->trainerModel->getTrainerById($trainerId);
        
        $data = [
            'fullName' => $_POST['fullName'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'specialization' => $_POST['specialization'],
            'experience' => $_POST['experience']
        ];

        try {
            // Handle avatar upload
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
                $fileName = $this->handleImageUpload($_FILES['avatar'], $trainer['avatar'] ?? null);
                if ($fileName) {
                    $data['avatar'] = $fileName;
                }
            }

            // Handle password update
            if (!empty($_POST['password'])) {
                if ($_POST['password'] !== $_POST['password_confirm']) {
                    throw new \Exception('Mật khẩu xác nhận không khớp.');
                }
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            if ($this->trainerModel->updateTrainer($trainerId, $data)) {
                $_SESSION['success'] = 'Cập nhật thông tin thành công.';
                $this->redirect('trainer/dashboard');
            } else {
                throw new \Exception('Có lỗi xảy ra khi cập nhật thông tin.');
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('trainer/profile/edit');
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $trainer = $this->trainerModel->getTrainerByUsername($username);

            if ($trainer && password_verify($password, $trainer['password'])) {
                // Set session variables
                $_SESSION['trainer_id'] = $trainer['id'];
                $_SESSION['trainer_name'] = $trainer['fullName'];
                $_SESSION['trainer_role'] = 'trainer';

                // Redirect to dashboard
                $this->redirect('/trainer/dashboard');
            } else {
                $this->view('Trainer/login', [
                    'error' => 'Tên đăng nhập hoặc mật khẩu không đúng'
                ]);
            }
        } else {
            $this->view('Trainer/login');
        }
    }

    public function dashboard()
    {
        // Check if trainer is logged in
        if (!isset($_SESSION['trainer_id']) || $_SESSION['trainer_role'] !== 'trainer') {
            $this->redirect('/trainer/login');
            return;
        }

        $trainerId = $_SESSION['trainer_id'];
        $trainer = $this->trainerModel->getTrainerById($trainerId);

        if (!$trainer) {
            $_SESSION['error'] = 'Không tìm thấy thông tin huấn luyện viên.';
            $this->redirect('/trainer/login');
            return;
        }

        // Get training schedule
        $trainingSchedule = $this->trainerModel->getTrainingSchedule($trainerId);

        $this->view('Trainer/dashboard', [
            'title' => 'Bảng điều khiển',
            'trainer' => $trainer,
            'trainingSchedule' => $trainingSchedule
        ]);
    }

    public function logout()
    {
        // Clear all session variables
        session_unset();
        // Destroy the session
        session_destroy();
        // Redirect to login page
        $this->redirect('trainer/login');
    }

    // Hàm mới để lấy danh sách huấn luyện viên
    public function getTrainers() {
        // Lấy dữ liệu từ cơ sở dữ liệu
        $trainers = $this->trainerModel->getAllTrainers();
        
        // Trả về dữ liệu dướidạng JSON
        echo json_encode($trainers);
    }
}
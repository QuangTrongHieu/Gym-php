<?php

namespace App\Controllers;

use App\Models\Trainer;
use Core\Helpers\FileUploader;

class TrainerController extends BaseController
{
    private $trainerModel;
    private $uploader;
    private const UPLOAD_DIR = 'trainers'; // Consistent upload directory

    public function __construct()
    {
        parent::__construct();
        $this->trainerModel = new Trainer();
        $this->uploader = new FileUploader();
    }

    private function handleImageUpload($file, $oldAvatar = null) {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new \Exception('Loại file không được hỗ trợ. Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WEBP)');
        }

        $uploadDir = ROOT_PATH . '/public/uploads/trainers/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Xóa ảnh cũ nếu có và không phải ảnh mặc định
        if ($oldAvatar && $oldAvatar !== 'default.jpg') {
            $oldAvatarPath = $uploadDir . $oldAvatar;
            if (file_exists($oldAvatarPath)) {
                unlink($oldAvatarPath);
            }
        }

        $fileName = uniqid() . '_' . time() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $fileName;
        }

        return false;
    }

    // public function trainerDetail($id)
    // {
    //     $trainer = $this->trainerModel->getTrainerById($id);
    //     $this->view('admin/trainer/trainerDetail', [
    //         'title' => 'Chi tiết huấn luyện viên',
    //         'trainer' => $trainer
    //     ]);
    // }

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

            try {
                // Handle avatar upload
                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                    $oldTrainer = $this->trainerModel->getTrainerById($id);
                    $fileName = $this->handleImageUpload($_FILES['avatar'], $oldTrainer['avatar'] ?? null);
                    if ($fileName) {
                        $data['avatar'] = $fileName;
                    }
                }

                if ($this->trainerModel->update($id, $data)) {
                    $_SESSION['success'] = 'Cập nhật thông tin huấn luyện viên thành công';
                } else {
                    throw new \Exception('Không thể cập nhật thông tin huấn luyện viên');
                }
            } catch (\Exception $e) {
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
            $trainer = $this->trainerModel->findById($id);
            if (!$trainer) {
                $_SESSION['error'] = 'Không tìm thấy huấn luyện viên';
                $this->redirect('admin/trainer');
                return;
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
            if ($this->trainerModel->delete($id)) {
                $_SESSION['success'] = 'Xóa huấn luyện viên thành công';
            } else {
                throw new \Exception('Không thể xóa huấn luyện viên');
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }
        $this->redirect('admin/trainer');
    }

    // public function export()
    // {
    //     require ROOT_PATH . '/vendor/autoload.php';
        
    //     $trainers = $this->trainerModel->getAllTrainers();
        
    //     $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();
        
    //     // Set headers
    //     $sheet->setCellValue('A1', 'ID');
    //     $sheet->setCellValue('B1', 'Họ tên');
    //     $sheet->setCellValue('C1', 'Username');
    //     $sheet->setCellValue('D1', 'Email');
    //     $sheet->setCellValue('E1', 'Ngày sinh');
    //     $sheet->setCellValue('F1', 'Giới tính');
    //     $sheet->setCellValue('G1', 'Số điện thoại');
    //     $sheet->setCellValue('H1', 'Chuyên môn');
    //     $sheet->setCellValue('I1', 'Kinh nghiệm');
    //     $sheet->setCellValue('J1', 'Chứng chỉ');
    //     $sheet->setCellValue('K1', 'Lương');
        
    //     // Style the header
    //     $headerStyle = [
    //         'font' => [
    //             'bold' => true,
    //         ],
    //         'fill' => [
    //             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
    //             'startColor' => [
    //                 'rgb' => 'E2EFDA',
    //             ],
    //         ],
    //     ];
    //     $sheet->getStyle('A1:K1')->applyFromArray($headerStyle);
        
    //     // Add data
    //     $row = 2;
    //     foreach ($trainers as $trainer) {
    //         $sheet->setCellValue('A' . $row, $trainer['id']);
    //         $sheet->setCellValue('B' . $row, $trainer['fullName']);
    //         $sheet->setCellValue('C' . $row, $trainer['username']);
    //         $sheet->setCellValue('D' . $row, $trainer['email']);
    //         $sheet->setCellValue('E' . $row, $trainer['dateOfBirth']);
    //         $sheet->setCellValue('F' . $row, $trainer['sex']);
    //         $sheet->setCellValue('G' . $row, $trainer['phone']);
    //         $sheet->setCellValue('H' . $row, $trainer['specialization']);
    //         $sheet->setCellValue('I' . $row, $trainer['experience']);
    //         $sheet->setCellValue('J' . $row, $trainer['certification']);
    //         $sheet->setCellValue('K' . $row, $trainer['salary']);
    //         $row++;
    //     }
        
    //     // Auto size columns
    //     foreach (range('A', 'K') as $col) {
    //         $sheet->getColumnDimension($col)->setAutoSize(true);
    //     }
        
    //     // Create Excel file
    //     $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
    //     // Set headers for download
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment;filename="danh_sach_hlv.xlsx"');
    //     header('Cache-Control: max-age=0');
        
    //     $writer->save('php://output');
    //     exit;
    // }

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
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
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
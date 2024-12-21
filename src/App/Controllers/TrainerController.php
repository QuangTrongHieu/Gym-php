<?php

namespace App\Controllers;

use App\Models\Trainer;

class TrainerController extends BaseController
{
    private $trainerModel;

    public function __construct()
    {
        parent::__construct();
        $this->trainerModel = new Trainer();
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
            
            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }
            
            try {
                if ($this->trainerModel->update($id, $data)) {
                    $_SESSION['success'] = 'Cập nhật thông tin huấn luyện viên thành công';
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật thông tin';
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

    public function export()
    {
        require ROOT_PATH . '/vendor/autoload.php';
        
        $trainers = $this->trainerModel->getAllTrainers();
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Họ tên');
        $sheet->setCellValue('C1', 'Username');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Ngày sinh');
        $sheet->setCellValue('F1', 'Giới tính');
        $sheet->setCellValue('G1', 'Số điện thoại');
        $sheet->setCellValue('H1', 'Chuyên môn');
        $sheet->setCellValue('I1', 'Kinh nghiệm');
        $sheet->setCellValue('J1', 'Chứng chỉ');
        $sheet->setCellValue('K1', 'Lương');
        
        // Style the header
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E2EFDA',
                ],
            ],
        ];
        $sheet->getStyle('A1:K1')->applyFromArray($headerStyle);
        
        // Add data
        $row = 2;
        foreach ($trainers as $trainer) {
            $sheet->setCellValue('A' . $row, $trainer['id']);
            $sheet->setCellValue('B' . $row, $trainer['fullName']);
            $sheet->setCellValue('C' . $row, $trainer['username']);
            $sheet->setCellValue('D' . $row, $trainer['email']);
            $sheet->setCellValue('E' . $row, $trainer['dateOfBirth']);
            $sheet->setCellValue('F' . $row, $trainer['sex']);
            $sheet->setCellValue('G' . $row, $trainer['phone']);
            $sheet->setCellValue('H' . $row, $trainer['specialization']);
            $sheet->setCellValue('I' . $row, $trainer['experience']);
            $sheet->setCellValue('J' . $row, $trainer['certification']);
            $sheet->setCellValue('K' . $row, $trainer['salary']);
            $row++;
        }
        
        // Auto size columns
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Create Excel file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="danh_sach_hlv.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function listTrainers()
    {
        $trainers = $this->trainerModel->findActiveTrainers();
        $this->view('RegisTrainer/list-trainers', [
            'title' => 'Đội ngũ Huấn luyện viên',
            'trainers' => $trainers
        ]);
    }
}
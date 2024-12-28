<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\MembershipRegistration;
use App\Models\Package;

class MemberController extends BaseController
{
    private $userModel;
    private $membershipModel;
    private $packageModel;

    public function __construct()
    {
        parent::__construct();
        $this->checkRole(['ADMIN']);
        $this->userModel = new User();
        $this->membershipModel = new MembershipRegistration();
        $this->packageModel = new Package();
    }

    public function index()
    {
        $members = $this->userModel->findAllMembers();
        $this->view('admin/members/index', [
            'title' => 'Quản lý Thành viên',
            'members' => $members
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => $_POST['username'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'fullName' => $_POST['fullName'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'dateOfBirth' => $_POST['dateOfBirth'],
                'sex' => $_POST['sex'],
                'eRole' => 'USER',
                'status' => 'active'
            ];

            if ($this->userModel->create($data)) {
                $_SESSION['success'] = 'Thêm thành viên thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
            $this->redirect('admin/member');
        }
    }

    public function edit($id)
    {
        try {
            $member = $this->userModel->findById($id);
            $packages = $this->packageModel->findAll();
            
            if (!$member) {
                $_SESSION['error'] = 'Không tìm thấy thành viên';
                $this->redirect('admin/member');
                return;
            }

            // Get membership data for display
            $membership = $this->membershipModel->findByUserId($id);
            if ($membership) {
                $member['package'] = $membership['packageId'];
                $member['startDate'] = date('Y-m-d', strtotime($membership['startDate']));
                $member['endDate'] = date('Y-m-d', strtotime($membership['endDate']));
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate input
                if (empty($_POST['fullName']) || empty($_POST['email']) || empty($_POST['phone'])) {
                    $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc';
                    $this->view('admin/members/edit', [
                        'member' => array_merge($member, $_POST),
                        'packages' => $packages
                    ]);
                    return;
                }

                // Validate email format
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['error'] = 'Email không hợp lệ';
                    $this->view('admin/members/edit', [
                        'member' => array_merge($member, $_POST),
                        'packages' => $packages
                    ]);
                    return;
                }

                // Validate phone number
                if (!preg_match('/^[0-9]{10}$/', $_POST['phone'])) {
                    $_SESSION['error'] = 'Số điện thoại không hợp lệ';
                    $this->view('admin/members/edit', [
                        'member' => array_merge($member, $_POST),
                        'packages' => $packages
                    ]);
                    return;
                }

                // Check if email exists for other users
                $existingUser = $this->userModel->findByEmail($_POST['email']);
                if ($existingUser && $existingUser['id'] != $id) {
                    $_SESSION['error'] = 'Email đã được sử dụng';
                    $this->view('admin/members/edit', [
                        'member' => array_merge($member, $_POST),
                        'packages' => $packages
                    ]);
                    return;
                }

                // Check if phone exists for other users
                $existingUser = $this->userModel->findByPhone($_POST['phone']);
                if ($existingUser && $existingUser['id'] != $id) {
                    $_SESSION['error'] = 'Số điện thoại đã được sử dụng';
                    $this->view('admin/members/edit', [
                        'member' => array_merge($member, $_POST),
                        'packages' => $packages
                    ]);
                    return;
                }

                // Update user data
                $userData = [
                    'id' => $id,
                    'fullName' => $_POST['fullName'],
                    'email' => $_POST['email'],
                    'phone' => $_POST['phone'],
                    'status' => $_POST['status']
                ];

                $success = $this->userModel->update($userData);

                if ($success) {
                    // Update membership data
                    $membershipData = [
                        'userId' => $id,
                        'packageId' => $_POST['package'],
                        'startDate' => $_POST['startDate'],
                        'endDate' => $_POST['endDate']
                    ];

                    if ($membership) {
                        // Update existing membership
                        $this->membershipModel->update($membershipData);
                    } else {
                        // Create new membership
                        $this->membershipModel->create($membershipData);
                    }

                    $_SESSION['success'] = 'Cập nhật thông tin thành công';
                    $this->redirect('admin/member');
                    return;
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật thông tin';
                    $this->view('admin/members/edit', [
                        'member' => array_merge($member, $_POST),
                        'packages' => $packages
                    ]);
                    return;
                }
            }

            $this->view('admin/members/edit', [
                'member' => $member,
                'packages' => $packages
            ]);

        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            $this->redirect('admin/member');
        }
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate input data
            $userData = [
                'fullName' => trim($_POST['fullName']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'status' => $_POST['status']
            ];

            $membershipData = [
                'package' => $_POST['package'],
                'startDate' => $_POST['startDate'],
                'endDate' => $_POST['endDate']
            ];

            // Validate required fields
            if (empty($userData['fullName']) || empty($userData['email']) || empty($userData['phone'])) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc';
                header("Location: /gym-php/admin/member/edit/{$id}");
                exit;
            }

            // Validate email format
            if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Email không hợp lệ';
                header("Location: /gym-php/admin/member/edit/{$id}");
                exit;
            }

            // Validate phone number format
            if (!preg_match('/^[0-9]{10}$/', $userData['phone'])) {
                $_SESSION['error'] = 'Số điện thoại không hợp lệ (phải có 10 chữ số)';
                header("Location: /gym-php/admin/member/edit/{$id}");
                exit;
            }

            // Check if email exists
            if ($this->userModel->findByEmail($userData['email']) && 
                $this->userModel->findByEmail($userData['email'])['id'] != $id) {
                $_SESSION['error'] = 'Email đã được sử dụng';
                header("Location: /gym-php/admin/member/edit/{$id}");
                exit;
            }

            // Check if phone exists
            if ($this->userModel->findByPhone($userData['phone']) && 
                $this->userModel->findByPhone($userData['phone'])['id'] != $id) {
                $_SESSION['error'] = 'Số điện thoại đã được sử dụng';
                header("Location: /gym-php/admin/member/edit/{$id}");
                exit;
            }

            // Validate dates
            $startDate = strtotime($membershipData['startDate']);
            $endDate = strtotime($membershipData['endDate']);
            if ($endDate < $startDate) {
                $_SESSION['error'] = 'Ngày kết thúc phải sau ngày bắt đầu';
                header("Location: /gym-php/admin/member/edit/{$id}");
                exit;
            }

            try {
                $this->userModel->beginTransaction();

                // Update user information
                if (!$this->userModel->update($id, $userData)) {
                    throw new \Exception('Không thể cập nhật thông tin hội viên');
                }

                // Update membership information
                $membership = $this->membershipModel->findByUserId($id);
                if ($membership) {
                    if (!$this->membershipModel->update($membership['id'], $membershipData)) {
                        throw new \Exception('Không thể cập nhật thông tin gói tập');
                    }
                } else {
                    $membershipData['userId'] = $id;
                    if (!$this->membershipModel->create($membershipData)) {
                        throw new \Exception('Không thể tạo gói tập mới');
                    }
                }

                $this->userModel->commit();
                $_SESSION['success'] = 'Cập nhật thông tin hội viên thành công';

            } catch (\Exception $e) {
                $this->userModel->rollback();
                $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            }

            header('Location: /gym-php/admin/member');
            exit;
        }
    }

    public function delete($id)
    {
        try {
            $member = $this->userModel->findById($id);
            if (!$member) {
                $_SESSION['error'] = 'Không tìm thấy hội viên';
                $this->redirect('admin/member');
                return;
            }

            $this->view('admin/members/delete', [
                'title' => 'Xóa hội viên',
                'member' => $member
            ]);
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            $this->redirect('admin/member');
        }
    }

    public function destroy($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Phương thức không hợp lệ';
            $this->redirect('admin/member');
            return;
        }

        try {
            $member = $this->userModel->findById($id);
            if (!$member) {
                $_SESSION['error'] = 'Không tìm thấy hội viên';
                $this->redirect('admin/member');
                return;
            }

            $this->userModel->beginTransaction();

            // Delete membership registration first
            $membership = $this->membershipModel->findByUserId($id);
            if ($membership) {
                if (!$this->membershipModel->delete($membership['id'])) {
                    throw new \Exception('Không thể xóa thông tin gói tập');
                }
            }

            // Then delete the user
            if (!$this->userModel->delete($id)) {
                throw new \Exception('Không thể xóa thông tin hội viên');
            }

            $this->userModel->commit();
            $_SESSION['success'] = 'Xóa hội viên thành công';

        } catch (\Exception $e) {
            $this->userModel->rollback();
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }

        $this->redirect('admin/member');
    }

    public function export()
    {
        require ROOT_PATH . '/vendor/autoload.php';
        
        $members = $this->userModel->getAll();
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Tên đăng nhập');
        $sheet->setCellValue('C1', 'Họ tên');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Số điện thoại');
        $sheet->setCellValue('F1', 'Ngày sinh');
        $sheet->setCellValue('G1', 'Giới tính');
        $sheet->setCellValue('H1', 'Gói hội viên');
        $sheet->setCellValue('I1', 'Ngày bắt đầu');
        $sheet->setCellValue('J1', 'Ngày kết thúc');
        $sheet->setCellValue('K1', 'Trạng thái');
        
        // Style the header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '4F81BD',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A1:K1')->applyFromArray($headerStyle);
        
        // Add data
        $row = 2;
        foreach ($members as $member) {
            $sheet->setCellValue('A' . $row, $member['id']);
            $sheet->setCellValue('B' . $row, $member['username']);
            $sheet->setCellValue('C' . $row, $member['fullName']);
            $sheet->setCellValue('D' . $row, $member['email']);
            $sheet->setCellValue('E' . $row, $member['phone']);
            $sheet->setCellValue('F' . $row, $member['dateOfBirth']);
            $sheet->setCellValue('G' . $row, $member['sex'] == 'Male' ? 'Nam' : ($member['sex'] == 'Female' ? 'Nữ' : 'Khác'));
            $sheet->setCellValue('H' . $row, ucfirst($member['package']));
            $sheet->setCellValue('I' . $row, $member['startDate']);
            $sheet->setCellValue('J' . $row, $member['endDate']);
            $sheet->setCellValue('K' . $row, $member['status'] == 'active' ? 'Hoạt động' : 'Không hoạt động');
            $row++;
        }
        
        // Style the data
        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A2:K' . ($row - 1))->applyFromArray($dataStyle);
        
        // Auto size columns
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Create Excel file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="danh_sach_hoi_vien.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
}
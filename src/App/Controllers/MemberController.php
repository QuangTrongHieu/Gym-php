<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Membership;
use App\Models\Package;

class MemberController extends BaseController
{
    private $userModel;
    private $membershipModel;
    private $packageModel;
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->checkRole(['ADMIN']);
        $this->userModel = new User();
        $this->membershipModel = new Membership();
        $this->packageModel = new Package();
        $this->db = $this->userModel->getConnection();
    }

    public function index()
    {
        $members = $this->membershipModel->findAll();
        $packages = $this->packageModel->findAll();
        
        $this->view('admin/member/index', [
            'title' => 'Quản lý Hội viên',
            'members' => $members,
            'packages' => $packages
        ], 'admin_layout');
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
                'status' => 'ACTIVE'
            ];

            // Validate input
            if (empty($data['username']) || empty($_POST['password']) || empty($data['fullName']) || 
                empty($data['email']) || empty($data['phone'])) {
                $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc';
                $this->redirect('admin/member');
                return;
            }

            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Email không hợp lệ';
                $this->redirect('admin/member');
                return;
            }

            // Validate phone number
            if (!preg_match('/^[0-9]{10}$/', $data['phone'])) {
                $_SESSION['error'] = 'Số điện thoại không hợp lệ';
                $this->redirect('admin/member');
                return;
            }

            try {
                $this->db->beginTransaction();

                // Create user
                $userId = $this->userModel->create($data);
                if (!$userId) {
                    throw new \Exception('Không thể tạo tài khoản');
                }

                // Create membership if package is selected
                if (!empty($_POST['package_id'])) {
                    // Get package price
                    $package = $this->packageModel->findById($_POST['package_id']);
                    if (!$package) {
                        throw new \Exception('Không tìm thấy gói tập');
                    }

                    $membershipData = [
                        'userId' => $userId,
                        'packageId' => $_POST['package_id'],
                        'startDate' => date('Y-m-d'),
                        'endDate' => date('Y-m-d', strtotime('+' . $package['duration'] . ' months')),
                        'status' => 'ACTIVE',
                        'paymentMethod' => 'CASH',
                        'amount' => $package['price'],
                        'qrImage' => null,
                        'paymentStatus' => 'PENDING'
                    ];

                    if (!$this->membershipModel->create($membershipData)) {
                        throw new \Exception('Không thể tạo gói tập');
                    }
                }

                $this->db->commit();
                $_SESSION['success'] = 'Thêm thành viên thành công';
                
            } catch (\Exception $e) {
                $this->db->rollback();
                $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
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
            $memberships = $this->membershipModel->findByUserId($id);
            if (!empty($memberships)) {
                $membership = $memberships[0]; // Get the most recent membership
                $member['package_id'] = $membership['packageId'];
                $member['startDate'] = date('Y-m-d', strtotime($membership['startDate']));
                $member['endDate'] = date('Y-m-d', strtotime($membership['endDate']));
                $member['membership_id'] = $membership['id'];
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate input
                if (empty($_POST['fullName']) || empty($_POST['email']) || empty($_POST['phone'])) {
                    $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc';
                    $this->view('admin/member/edit', [
                        'member' => array_merge($member, $_POST),
                        'packages' => $packages
                    ]);
                    return;
                }

                // Validate email format
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['error'] = 'Email không hợp lệ';
                    $this->view('admin/member/edit', [
                        'member' => array_merge($member, $_POST),
                        'packages' => $packages
                    ]);
                    return;
                }

                // Validate phone number
                if (!preg_match('/^[0-9]{10}$/', $_POST['phone'])) {
                    $_SESSION['error'] = 'Số điện thoại không hợp lệ';
                    $this->view('admin/member/edit', [
                        'member' => array_merge($member, $_POST),
                        'packages' => $packages
                    ]);
                    return;
                }

                // Check if email exists for other users
                $existingUser = $this->userModel->findByEmail($_POST['email']);
                if ($existingUser && $existingUser['id'] != $id) {
                    $_SESSION['error'] = 'Email đã được sử dụng';
                    $this->view('admin/member/edit', [
                        'member' => array_merge($member, $_POST),
                        'packages' => $packages
                    ]);
                    return;
                }

                // Check if phone exists for other users
                $existingUser = $this->userModel->findByPhone($_POST['phone']);
                if ($existingUser && $existingUser['id'] != $id) {
                    $_SESSION['error'] = 'Số điện thoại đã được sử dụng';
                    $this->view('admin/member/edit', [
                        'member' => array_merge($member, $_POST),
                        'packages' => $packages
                    ]);
                    return;
                }

                try {
                    $this->db->beginTransaction();

                    // Update user data
                    $userData = [
                        'fullName' => $_POST['fullName'],
                        'email' => $_POST['email'],
                        'phone' => $_POST['phone'],
                        'status' => $_POST['status'] ?? 'ACTIVE',
                        'dateOfBirth' => $_POST['dateOfBirth'],
                        'sex' => $_POST['sex']
                    ];

                    if (!$this->userModel->update($id, $userData)) {
                        throw new \Exception('Không thể cập nhật thông tin hội viên');
                    }

                    // Update membership data
                    $package = $this->packageModel->findById($_POST['package_id']);
                    if (!$package) {
                        throw new \Exception('Không tìm thấy gói tập');
                    }

                    $membershipData = [
                        'userId' => $id,
                        'packageId' => $_POST['package_id'],
                        'startDate' => $_POST['startDate'],
                        'endDate' => $_POST['endDate'],
                        'status' => 'ACTIVE',
                        'paymentMethod' => 'CASH',
                        'amount' => $package['price'],
                        'qrImage' => null,
                        'paymentStatus' => 'PAID'
                    ];

                    if (!empty($memberships)) {
                        $membershipId = $memberships[0]['id'];
                        if (!$this->membershipModel->update($membershipId, $membershipData)) {
                            throw new \Exception('Không thể cập nhật thông tin gói tập');
                        }
                    } else {
                        if (!$this->membershipModel->create($membershipData)) {
                            throw new \Exception('Không thể tạo gói tập mới');
                        }
                    }

                    $this->db->commit();
                    $_SESSION['success'] = 'Cập nhật thông tin thành công';
                    $this->redirect('admin/member');

                } catch (\Exception $e) {
                    $this->db->rollback();
                    $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
                    $this->view('admin/member/edit', [
                        'member' => array_merge($member, $_POST),
                        'packages' => $packages
                    ]);
                }
                return;
            }

            $this->view('admin/member/edit', [
                'member' => $member,
                'packages' => $packages
            ]);

        } catch (\Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            $this->redirect('admin/member');
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

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->db->beginTransaction();
                try {
                    // Delete memberships first
                    $memberships = $this->membershipModel->findByUserId($id);
                    foreach ($memberships as $membership) {
                        if (!$this->membershipModel->delete($membership['id'])) {
                            throw new \Exception('Không thể xóa thông tin gói tập');
                        }
                    }

                    // Then delete user
                    if (!$this->userModel->delete($id)) {
                        throw new \Exception('Không thể xóa thông tin hội viên');
                    }

                    $this->db->commit();
                    $_SESSION['success'] = 'Xóa hội viên thành công';
                } catch (\Exception $e) {
                    $this->db->rollback();
                    $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
                }
                $this->redirect('admin/member');
                return;
            }

            $this->view('admin/member/delete', [
                'title' => 'Xóa hội viên',
                'member' => $member
            ]);
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            $this->redirect('admin/member');
        }
    }
}
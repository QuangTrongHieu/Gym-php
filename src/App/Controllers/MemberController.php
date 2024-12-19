<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\MembershipRegistration;

class MemberController extends BaseController
{
    private $userModel;
    private $membershipModel;

    public function __construct()
    {
        parent::__construct();
        $this->checkRole(['ADMIN']);
        $this->userModel = new User();
        $this->membershipModel = new MembershipRegistration();
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
        $member = $this->userModel->findById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'fullName' => $_POST['fullName'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'dateOfBirth' => $_POST['dateOfBirth'],
                'sex' => $_POST['sex'],
                'status' => $_POST['status']
            ];

            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            if ($this->userModel->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật thành viên thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
            $this->redirect('admin/member');
        }

        $this->view('admin/members/edit', [
            'title' => 'Sửa thông tin thành viên',
            'member' => $member
        ]);
    }

    public function delete($id)
    {
        if ($this->userModel->delete($id)) {
            $_SESSION['success'] = 'Xóa thành viên thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra';
        }
        $this->redirect('admin/member');
    }
} 
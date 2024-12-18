<?php

namespace App\Controllers;

use App\Models\Membership;

class MembershipController extends BaseController
{
    private $membershipModel;

    public function __construct()
    {
        $this->membershipModel = new Membership();
    }

    public function index()
    {
        $memberships = $this->membershipModel->findAll();
        $this->view('membership/index', [
            'title' => 'Quản lý Hội viên',
            'memberships' => $memberships
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'userId' => $_POST['userId'],
                'packageId' => $_POST['packageId'],
                'startDate' => $_POST['startDate'],
                'endDate' => $_POST['endDate'],
                'status' => $_POST['status'],
                'paymentId' => $_POST['paymentId']
            ];
            $this->membershipModel->create($data);
            // Có thể thêm logic điều hướng hoặc thông báo sau khi tạo
        }
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'userId' => $_POST['userId'],
                'packageId' => $_POST['packageId'],
                'startDate' => $_POST['startDate'],
                'endDate' => $_POST['endDate'],
                'status' => $_POST['status'],
                'paymentId' => $_POST['paymentId']
            ];
            $this->membershipModel->update($id, $data);
            // Có thể thêm logic điều hướng hoặc thông báo sau khi cập nhật
        }
        // Có thể thêm logic để lấy thông tin hội viên hiện tại và hiển thị
    }

    public function delete($id)
    {
        try {
            // Kiểm tra xem hội viên có tồn tại không
            $membership = $this->membershipModel->find($id);
            if (!$membership) {
                $_SESSION['error'] = 'Không tìm thấy hội viên này';
                header('Location: /membership');
                return;
            }

            // Thực hiện xóa hội viên
            $this->membershipModel->delete($id);
            
            // Thêm thông báo thành công vào session
            $_SESSION['success'] = 'Đã xóa hội viên thành công';
            
            // Chuyển hướng về trang danh sách
            header('Location: /membership');
        } catch (\Exception $e) {
            // Xử lý lỗi nếu có
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa hội viên: ' . $e->getMessage();
            header('Location: /membership');
        }
    }
} 
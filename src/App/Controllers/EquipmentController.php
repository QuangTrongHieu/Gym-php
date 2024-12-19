<?php

namespace App\Controllers;

use App\Models\Equipment;

class EquipmentController extends BaseController
{
    private $equipmentModel;

    public function __construct()
    {
        $this->equipmentModel = new Equipment();
    }

    public function index()
    {
        $packages = $this->equipmentModel->findAll();
        $this->view('admin/equipment/index', [
            'title' => 'Quản lý Gói tập',
            'packages' => $packages
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate dữ liệu
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'purchaseDate' => $_POST['purchaseDate'],
                'price' => floatval($_POST['price']),
                'status' => $_POST['status'],
                'lastMaintenanceDate' => !empty($_POST['lastMaintenanceDate']) ? $_POST['lastMaintenanceDate'] : null,
                'nextMaintenanceDate' => !empty($_POST['nextMaintenanceDate']) ? $_POST['nextMaintenanceDate'] : null
            ];

            // Kiểm tra validate
            $errors = [];
            if(empty($data['name'])) {
                $errors['name'] = 'Vui lòng nhập tên thiết bị';
            }
            if(empty($data['purchaseDate'])) {
                $errors['purchaseDate'] = 'Vui lòng nhập ngày mua';
            }
            if($data['price'] <= 0) {
                $errors['price'] = 'Giá phải lớn hơn 0';
            }

            if(empty($errors)) {
                if($this->equipmentModel->create($data)) {
                    $_SESSION['success'] = 'Thêm thiết bị thành công';
                    header('Location: /admin/equipment');
                    exit();
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra';
                }
            }

            $title = 'Thêm thiết bị mới';
            include 'views/admin/equipment/create.php';
        } else {
            $title = 'Thêm thiết bị mới';
            include 'views/admin/equipment/create.php';
        }
    }

    public function edit($id)
    {
        $equipment = $this->equipmentModel->findById($id);
        if(!$equipment) {
            $_SESSION['error'] = 'Không tìm thấy thiết bị';
            header('Location: /admin/equipment');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate dữ liệu
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'purchaseDate' => $_POST['purchaseDate'],
                'price' => floatval($_POST['price']),
                'status' => $_POST['status'],
                'lastMaintenanceDate' => !empty($_POST['lastMaintenanceDate']) ? $_POST['lastMaintenanceDate'] : null,
                'nextMaintenanceDate' => !empty($_POST['nextMaintenanceDate']) ? $_POST['nextMaintenanceDate'] : null
            ];

            // Kiểm tra validate
            $errors = [];
            if(empty($data['name'])) {
                $errors['name'] = 'Vui lòng nhập tên thiết bị';
            }
            if(empty($data['purchaseDate'])) {
                $errors['purchaseDate'] = 'Vui lòng nhập ngày mua';
            }
            if($data['price'] <= 0) {
                $errors['price'] = 'Giá phải lớn hơn 0';
            }

            if(empty($errors)) {
                if($this->equipmentModel->update($id, $data)) {
                    $_SESSION['success'] = 'Cập nhật thiết bị thành công';
                    header('Location: /admin/equipment');
                    exit();
                } else {
                    $_SESSION['error'] = 'Có lỗi xảy ra';
                }
            }

            $this->view('admin/equipment/edit', [
                'title' => 'Chỉnh sửa thiết bị',
                'equipment' => $equipment,
                'errors' => $errors
            ]);
        } else {
            $this->view('admin/equipment/edit', [
                'title' => 'Chỉnh sửa thiết bị',
                'equipment' => $equipment
            ]);
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if($this->equipmentModel->delete($id)) {
                $_SESSION['success'] = 'Xóa thiết bị thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
        }
        header('Location: /admin/equipment');
        exit();
    }
} 
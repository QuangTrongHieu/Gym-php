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
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'duration' => $_POST['duration'],
                'price' => $_POST['price'],
                'status' => 'active'
            ];
            
            if ($this->trainerModel->create($data)) {
                $_SESSION['success'] = 'Thêm gói tập thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
            $this->redirect('admin/trainer');
        }
    }

    public function edit($id)
    {
        $trainer = $this->trainerModel->getById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'duration' => $_POST['duration'],
                'price' => $_POST['price']
            ];
            
            if ($this->trainerModel->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật gói tập thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
            $this->redirect('admin/trainer');
        }
        
        $this->view('admin/trainer/edit', [
            'title' => 'Sửa gói tập',
            'trainer' => $trainer
        ]);
    }

    public function delete($id)
    {
        $trainers = $this->trainerModel->getAllTrainers();
        if ($this->trainerModel->delete($id)) {
            $_SESSION['success'] = 'Xóa gói tập thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra';
        }
        $this->view('admin/trainer/delete', [
            'trainers' => $trainers
        ]);
    }
} 
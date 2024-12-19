<?php

namespace App\Controllers;

use App\Models\Package;

class PackagesController extends BaseController
{
    private $packageModel;

    public function __construct()
    {
        parent::__construct();
        $this->packageModel = new Package();
    }

    public function index()
    {
        $packages = $this->packageModel->findAll();
        $this->view('admin/packages/index', [
            'title' => 'Quản lý Gói tập',
            'packages' => $packages
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
            
            if ($this->packageModel->create($data)) {
                $_SESSION['success'] = 'Thêm gói tập thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
            $this->redirect('admin/packages/');
        }
    }

    public function edit($id)
    {
        $package = $this->packageModel->findById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'duration' => $_POST['duration'],
                'price' => $_POST['price']
            ];
            
            if ($this->packageModel->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật gói tập thành công';
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra';
            }
            $this->redirect('admin/packages');
        }
        
        $this->view('admin/packages/edit', [
            'title' => 'Sửa gói tập',
            'package' => $package
        ]);
    }

    public function delete($id)
    {
        if ($this->packageModel->delete($id)) {
            $_SESSION['success'] = 'Xóa gói tập thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra';
        }
        $this->redirect('admin/packages');
    }
} 
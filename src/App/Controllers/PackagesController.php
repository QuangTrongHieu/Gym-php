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
                'status' => $_POST['status']
            ];

            if ($this->packageModel->create($data)) {
                $_SESSION['success'] = 'Thêm gói tập thành công';
                $this->redirect('/admin/packages');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi thêm gói tập';
                $this->redirect('/admin/packages/create');
            }
        }

        $this->view('admin/packages/create', [
            'title' => 'Thêm gói tập mới'
        ]);
    }

    public function edit($id)
    {
        $package = $this->packageModel->findById($id);
        
        if (!$package) {
            $_SESSION['error'] = 'Không tìm thấy gói tập';
            $this->redirect('/admin/packages');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'duration' => $_POST['duration'],
                'price' => $_POST['price'],
                'status' => $_POST['status']
            ];

            if ($this->packageModel->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật gói tập thành công';
                $this->redirect('/admin/packages');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật gói tập';
            }
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
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa gói tập';
        }
        $this->redirect('/admin/packages');
    }

    public function listpackages()
    {
        $packages = $this->packageModel->findActivePackages();
        $this->view('packages/list', [
            'title' => 'Danh sách gói tập',
            'packages' => $packages
        ]);
    }
}
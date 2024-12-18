<?php

namespace App\Controllers;

use App\Models\Package;

class PackageController extends BaseController
{
    private $packageModel;

    public function __construct()
    {
        $this->packageModel = new Package();
    }

    public function index()
    {
        $packages = $this->packageModel->findAll();
        $this->view('package/index', [
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
                'price' => $_POST['price'],
                'duration' => $_POST['duration'],
                'maxFreeze' => $_POST['maxFreeze'],
                'benefits' => $_POST['benefits'],
                'status' => $_POST['status']
            ];

            if ($this->packageModel->create($data)) {
                // Chuyển hướng hoặc thông báo thành công
            } else {
                // Thông báo lỗi
            }
        } else {
            $this->view('package/create', [
                'title' => 'Tạo Gói tập mới'
            ]);
        }
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'duration' => $_POST['duration'],
                'maxFreeze' => $_POST['maxFreeze'],
                'benefits' => $_POST['benefits'],
                'status' => $_POST['status']
            ];

            if ($this->packageModel->update($id, $data)) {
                // Chuyển hướng hoặc thông báo thành công
            } else {
                // Thông báo lỗi
            }
        } else {
            $package = $this->packageModel->findById($id);
            $this->view('package/edit', [
                'title' => 'Chỉnh sửa Gói tập',
                'package' => $package
            ]);
        }
    }

    public function delete($id)
    {
        if ($this->packageModel->delete($id)) {
            // Chuyển hướng hoặc thông báo thành công
        } else {
            // Thông báo lỗi
        }
    }
} 
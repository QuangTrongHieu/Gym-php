<?php

namespace App\Controllers;

use App\Models\Revenue;

class RevenueController extends BaseController
{
    private $revenueModel;

    public function __construct()
    {
        $this->revenueModel = new Revenue();
    }

    public function index()
    {
        $revenueData = $this->revenueModel->getRevenueByPackage();
        $this->view('revenue/index', [
            'title' => 'Thống kê Doanh thu theo Gói tập',
            'revenueData' => $revenueData
        ]);
    }
} 
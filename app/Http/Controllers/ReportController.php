<?php

namespace App\Http\Controllers;

use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Report\ReportRepositoryInterface;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportRepo;
    protected $customerRepo;

    public function __construct(ReportRepositoryInterface $reportRepo, CustomerRepositoryInterface $customerRepo)
    {
        $this->reportRepo = $reportRepo;
        $this->customerRepo = $customerRepo;
    }

    public function index()
    {
        $reports = $this->reportRepo->search();
        return view('management.report.index', ['reports' => $reports]);
    }
}

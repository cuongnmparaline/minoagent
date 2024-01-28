<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Report;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Report\ReportRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $customerRepo;
    protected $reportRepo;

    public function __construct(CustomerRepositoryInterface $customerRepo, ReportRepositoryInterface $reportRepo)
    {
        $this->customerRepo = $customerRepo;
        $this->reportRepo = $reportRepo;
    }

    public function dashboard() {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role == 2) {
            $customers = $this->customerRepo->getByAdmin();
        } else {
            $customers = $this->customerRepo->getAll(false);
        }
        $currentMonth = Carbon::now()->month;
        $reports = Report::whereRaw("MONTH(date) = $currentMonth")->get();
        return view("management.dashboard", ['customers' => $customers, 'reports' => $reports]);
    }

    public function realSpend() {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role == 2) {
            $customers = $this->customerRepo->getByAdmin();
        } else {
            $customers = $this->customerRepo->getAll(false);
        }
        $currentMonth = Carbon::now()->month;
        $reports = Report::whereRaw("MONTH(date) = $currentMonth")->get();
        return view("management.realSpend", ['customers' => $customers, 'reports' => $reports]);
    }
}

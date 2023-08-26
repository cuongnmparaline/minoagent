<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Report;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Report\ReportRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        $reports = $this->reportRepo->getAll();

        foreach ($reports as $report) {
            $amountFee = $report->amount * ($report->account->customer->fee/100);
            $this->reportRepo->update($report->id, ['amount_fee' => $amountFee]);
        }


        $customers = $this->customerRepo->getAll();
        $currentMonth = Carbon::now()->month;
        $reports = Report::whereRaw("MONTH(date) = $currentMonth")->get();
        return view("management.dashboard", ['customers' => $customers, 'reports' => $reports]);
    }
}

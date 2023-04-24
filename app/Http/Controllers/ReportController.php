<?php

namespace App\Http\Controllers;

use App\Repositories\Account\AccountRepository;
use App\Repositories\Report\ReportRepositoryInterface;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportRepo;
    protected $accountRepo;

    public function __construct(ReportRepositoryInterface $reportRepo, AccountRepository $accountRepo)
    {
        $this->reportRepo = $reportRepo;
        $this->accountRepo = $accountRepo;
    }

    public function index()
    {
        $reports = $this->reportRepo->search();
        return view('management.report.index', ['reports' => $reports]);
    }

    public function create() {
        $accounts = $this->accountRepo->getAll();
        return view('management.report.create', ['accounts' => $accounts]);
    }
}

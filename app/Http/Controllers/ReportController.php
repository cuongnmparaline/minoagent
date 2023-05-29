<?php

namespace App\Http\Controllers;

use App\Http\Requests\Report\CreateRequest;
use App\Repositories\Account\AccountRepository;
use App\Repositories\Report\ReportRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ReportsImport;

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

    public function store(CreateRequest $createRequest) {
        try {
            DB::beginTransaction();
            $data = request()->all();
            $this->reportRepo->create($data);
            DB::commit();
            session()->flash('success', __('messages.reportCreated'));
        } catch (\Exception $e) {
            Log::error('Report Create Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            DB::rollBack();
            session()->flash('error', __('messages.createFail'));
        }

        return redirect()->route('management.report');
    }

    public function edit($id) {
        try {
            $accounts = $this->accountRepo->getAll();
            $report = $this->reportRepo->find($id);
            session()->put('customer_data', $report);
        } catch (\Exception $e) {
            session()->flash('error', __('messages.customerNotFound'));
            return redirect()->route('management.customer');
        }
        return view('management.report.edit', ['report' => $report, 'accounts' => $accounts]);
    }

    public function import(){
        return view('management.report.import');
    }

    public function saveImport(){
        Excel::import(new ReportsImport, request()->file('import'));
    }
}

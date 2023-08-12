<?php

namespace App\Http\Controllers;

use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Http\Requests\Report\CreateRequest;
use App\Http\Requests\Report\ExportRequest;
use App\Models\Report;
use App\Repositories\Account\AccountRepository;
use App\Repositories\Customer\CustomerRepository;
use App\Repositories\Report\ReportRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ReportsImport;

class ReportController extends Controller
{
    protected $reportRepo;
    protected $accountRepo;

    public function __construct(ReportRepositoryInterface $reportRepo, AccountRepository $accountRepo, CustomerRepository $customerRepo)
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

    public function saveImport(ExportRequest $request){
        try {
            $currencies = Currency::rates()
                ->latest()
                ->base('USD')
                ->get();
            $date = request('date') ? request('date') : Carbon::now()->format('Y-m-d');
            $excel = Excel::import(new ReportsImport($date, $currencies), request()->file('reportImport'));
            return redirect()->route('management.report');
        } catch (\Exception $e) {
            dd($e);
        }
    }
}

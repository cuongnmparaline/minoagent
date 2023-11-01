<?php

namespace App\Http\Controllers;

use App\Exports\AccountsExport;
use App\Exports\CustomerExport;
use App\Http\Requests\Customer\EditRequest;
use App\Models\Account;
use App\Models\Group;
use App\Models\History;
use App\Repositories\Account\AccountRepositoryInterface;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\History\HistoryRepositoryInterface;
use App\Repositories\Report\ReportRepository;
use App\Repositories\Report\ReportRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\Customer\CreateRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\Exception;

class CustomerController extends Controller
{
    protected $customerRepo;
    protected $accountRepo;
    protected $historyRepo;
    protected $reportRepo;
    protected $groupRepo;

    public function __construct(CustomerRepositoryInterface $customerRepo, ReportRepository $reportRepo, AccountRepositoryInterface $accountRepo, GroupRepositoryInterface $groupRepo, HistoryRepositoryInterface $historyRepo)
    {
        $this->customerRepo = $customerRepo;
        $this->reportRepo = $reportRepo;
        $this->accountRepo = $accountRepo;
        $this->historyRepo = $historyRepo;
        $this->groupRepo = $groupRepo;
    }

    public function index()
    {
        $customers = $this->customerRepo->search();
        return view('management.customer.index', ['customers' => $customers]);
    }

    public function create() {
        return view('management.customer.create');
    }

    public function store(CreateRequest $createRequest) {
        try {
            DB::beginTransaction();
            $data = request()->all();
            $this->customerRepo->create($data);
            session()->flash('success', __('messages.customerCreated'));
            DB::commit();
        } catch (\Exception $e) {
            Log::error('Customer Create Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            DB::rollBack();
            session()->flash('error', __('messages.createFail'));
        }

        return redirect()->route('management.customer');
    }

    public function show($id) {
        $customer = $this->customerRepo->find($id);
        return view('management.customer.show', ['customer' => $customer]);
    }

    public function edit($id) {
        try {
            $customer = $this->customerRepo->find($id);
            session()->put('customer_data', $customer);
        } catch (\Exception $e) {
            Log::error('Customer Edit Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            session()->flash('error', __('messages.customerNotFound'));
            return redirect()->route('management.customer');
        }
        return view('management.customer.edit', ['customer' => $customer]);
    }

    public function update(EditRequest $editRequest, $id)
    {
        try {
            DB::beginTransaction();
            $data = request()->all();
            if(!empty($data['addBalance'])) {
                $data['balance'] += $data['addBalance'];
            }
            if (request()->get('password') == null) {
                $data = Arr::except($data, 'password');
            }
            $result = $this->customerRepo->update($id, $data);
            if (!empty($result)) {
                session()->flash('success', __('messages.customerUpdated'));
            }
            DB::commit();
        } catch (Exception $e) {
            Log::error('Update customer Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            DB::rollBack();
            session()->flash('error', __('messages.updateFail'));
        }
        return redirect()->route('management.customer');
    }

    public function delete($id) {
        DB::beginTransaction();
        try {
            $this->customerRepo->delete($id);
            $accounts = Account::where('customer_id', $id)->get();
            $histories = History::where('customer_id', $id)->get();
            $groups = Group::where('customer_id', $id)->get();
            foreach ($accounts as $account) {
                $this->accountRepo->delete($account->id);
            }
            foreach ($histories as $history) {
                $this->historyRepo->delete($history->id);
            }
            foreach ($groups as $group) {
                $this->groupRepo->delete($group->id);
            }
            DB::commit();
            session()->flash('success', __('messages.customerDeleted'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Account Delete Error ', ['admin' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            session()->flash('error', __('messages.deleteFail'));
        }

        return redirect()->route('management.customer');
    }

    public function calBalance($id) {
        $accounts = $this->customerRepo->accounts;
    }

    public function exportAccount($id){
        $customer = $this->customerRepo->find($id);
        return Excel::download(new AccountsExport($id), $customer->name.' report.xlsx');
    }

    public function export(){
        return Excel::download(new CustomerExport(\request()->get("month")), 'Customer Export.xlsx');
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\AccountsExport;
use App\Http\Requests\Customer\EditRequest;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Report\ReportRepository;
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

    public function __construct(CustomerRepositoryInterface $customerRepo, ReportRepository $reportRepo)
    {
        $this->customerRepo = $customerRepo;
        $this->reportRepo = $reportRepo;
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
        try {
            $this->customerRepo->delete($id);
            session()->flash('success', __('messages.customerDeleted'));
        } catch (Exception $e) {
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
}

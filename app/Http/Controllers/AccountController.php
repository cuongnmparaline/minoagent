<?php

namespace App\Http\Controllers;

use App\Repositories\Account\AccountRepositoryInterface;
use App\Repositories\Customer\CustomerRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Account\CreateRequest;
use App\Http\Requests\Account\EditRequest;
use Mockery\Exception;

class AccountController extends Controller
{
    protected $customerRepo;
    protected $accountRepo;

    public function __construct(AccountRepositoryInterface $accountRepo, CustomerRepositoryInterface $customerRepo)
    {
        $this->accountRepo = $accountRepo;
        $this->customerRepo = $customerRepo;
    }

    public function index()
    {
        $accounts = $this->accountRepo->search();
        return view('management.account.index', ['accounts' => $accounts]);
    }

    public function create() {
        $customers = $this->customerRepo->getAll();
        return view('management.account.create', ['customers' => $customers]);
    }

    public function store(CreateRequest $createRequest) {
        try {
            DB::beginTransaction();
            $data = request()->all();
            $this->accountRepo->create($data);
            session()->flash('success', __('messages.accountCreated'));
            DB::commit();
        } catch (\Exception $e) {
            Log::error('Account Create Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            DB::rollBack();
            session()->flash('error', __('messages.createFail'));
        }

        return redirect()->route('management.account');
    }

    public function edit($id) {
        try {
            $account = $this->accountRepo->find($id);
            $customers = $this->customerRepo->getAll();
            session()->put('account_data', $account);
        } catch (\Exception $e) {
            Log::error('Account Edit Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            session()->flash('error', __('messages.customerNotFound'));
            return redirect()->route('management.customer');
        }
        return view('management.account.edit', ['account' => $account, 'customers' => $customers]);
    }

    public function update(EditRequest $editRequest, $id)
    {
        try {
            DB::beginTransaction();
            $data = request()->all();
            $result = $this->accountRepo->update($id, $data);
            if (!empty($result)) {
                session()->flash('success', __('messages.accountUpdated'));
            }
            DB::commit();
        } catch (Exception $e) {
            Log::error('Update account Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            DB::rollBack();
            session()->flash('error', __('messages.updateFail'));
        }
        return redirect()->route('management.account');
    }
}

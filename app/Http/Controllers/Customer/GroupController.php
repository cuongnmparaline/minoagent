<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\EditRequest;
use App\Models\Account;
use App\Repositories\Account\AccountRepositoryInterface;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Http\Requests\Group\CreateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{
    protected $customerRepo;
    protected $groupRepo;
    protected $accountRepo;

    public function __construct(CustomerRepositoryInterface $customerRepo, GroupRepositoryInterface $groupRepo, AccountRepositoryInterface $accountRepo)
    {
        $this->customerRepo = $customerRepo;
        $this->groupRepo = $groupRepo;
        $this->accountRepo = $accountRepo;
    }

    public function index()
    {
        $groups = $this->groupRepo->search();
        return view('customer.group.index', ['groups' => $groups]);
    }

    public function create() {
        return view('customer.group.create');
    }

    public function store(CreateRequest $createRequest) {
        try {
            DB::beginTransaction();
            $data = request()->all();
            $data['customer_id'] = $id = Auth::guard('customer')->id();
            $data['ins_id'] = $id = Auth::guard('customer')->id();
            $this->groupRepo->create($data);
            session()->flash('success', __('messages.groupCreated'));
            DB::commit();
        } catch (\Exception $e) {
            Log::error('Group Create Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            DB::rollBack();
            session()->flash('error', __('messages.createFail'));
        }

        return redirect()->route('customer.group');
    }

    public function show($id) {
        $accounts = Account::where('group_id', $id)->get();
        return view('customer.group.show', ['accounts' => $accounts, 'groupId' => $id]);
    }

    public function edit($id) {
        try {
            $group = $this->groupRepo->find($id);
            $customers = $this->customerRepo->getAll();
            session()->put('group_data', $group);
        } catch (\Exception $e) {
            Log::error('Group Edit Error ', ['admin_id' => Auth::guard('customer')->id(), 'error' => $e->getMessage()]);
            session()->flash('error', __('messages.customerNotFound'));
            return redirect()->route('management.group');
        }
        return view('customer.group.edit', ['group' => $group, 'customers' => $customers]);
    }

    public function update(EditRequest $editRequest, $id)
    {
        try {
            DB::beginTransaction();
            $data = request()->all();
            $result = $this->groupRepo->update($id, $data);
            if (!empty($result)) {
                session()->flash('success', __('messages.groupUpdated'));
            }
            DB::commit();
        } catch (\Exception $e) {
            Log::error('Update group Error ', ['admin_id' => Auth::guard('customer')->id(), 'error' => $e->getMessage()]);
            DB::rollBack();
            session()->flash('error', __('messages.updateFail'));
        }
        return redirect()->route('customer.group');
    }

    public function delete($id) {
        try {
            $this->groupRepo->delete($id);
            session()->flash('success', __('messages.groupDeleted'));
        } catch (\Exception $e) {
            Log::error('Group Delete Error ', ['admin' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            session()->flash('error', __('messages.deleteFail'));
        }

        return redirect()->route('customer.group');
    }

    public function addAccount($id) {
        try {
            $customerId = Auth::guard('customer')->id();
            $group = $this->groupRepo->find($id);
            $accountsNotIn = Account::whereNotIn('group_id', [$id])->where('customer_id', $customerId)->get();
            $accountsNull = Account::where('group_id', null)->where('customer_id', $customerId)->get();
            $accounts = $accountsNotIn->merge($accountsNull);
        } catch (\Exception $e) {
            Log::error('Group Delete Error ', ['admin' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            session()->flash('error', __('messages.deleteFail'));
        }

        return view('customer.group.addAccount', ['group' => $group, 'accounts' => $accounts]);
    }

    public function saveToGroup($id, $accountId){
        try {
            $account = $this->accountRepo->find($accountId);
            if ($account['customer_id'] != Auth::guard('customer')->id()) {
                session()->flash('error', "Add account fail!");
            } else {
                $this->accountRepo->update($accountId, ['group_id' => $id]);
                session()->flash('success', "Add to group complete!");
            }
        } catch (\Exception $e) {
            Log::error('Add to group error', ['admin' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            session()->flash('error', 'Add account fail!');
        }

        return redirect()->route('customer.group.addAccount', ['id' => $id]);
    }

    public function remove($id, $accountId){
        try {
            $account = $this->accountRepo->find($accountId);
            if ($account['customer_id'] != Auth::guard('customer')->id()) {
                session()->flash('error', "Add account fail!");
            } else {
                $this->accountRepo->update($accountId, ['group_id' => null]);
                session()->flash('success', "Remove from group complete!");
            }
        } catch (\Exception $e) {
            Log::error('Remove from group error', ['admin' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            session()->flash('error', __('messages.deleteFail'));
        }

        return redirect()->route('customer.group.show', ['id' => $id]);
    }
}

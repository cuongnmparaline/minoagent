<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\EditRequest;
use App\Repositories\Admin\AdminRepositoryInterface;
use App\Repositories\Customer\CustomerRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Admin\CreateRequest;

class AdminController extends Controller
{
    protected $customerRepo;
    protected $adminRepo;

    public function __construct(AdminRepositoryInterface $adminRepo, CustomerRepositoryInterface $customerRepo)
    {
        $this->adminRepo = $adminRepo;
        $this->customerRepo = $customerRepo;
    }

    public function index()
    {
        $totalAdmins = $this->adminRepo->getAll();
        return view('management.admin.index', ['admins' => $totalAdmins]);
    }

    public function create() {
        return view('management.admin.create');
    }

    public function store(CreateRequest $createRequest) {
        try {
            DB::beginTransaction();
            $data = request()->all();
            $this->adminRepo->create($data);
            session()->flash('success', __('messages.accountCreated'));
            DB::commit();
        } catch (\Exception $e) {
            Log::error('Admin Create Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            DB::rollBack();
            session()->flash('error', __('messages.createFail'));
        }

        return redirect()->route('management.admin');
    }

    public function edit($id) {
        try {
            $admin = $this->adminRepo->find($id);
            session()->put('admin_data', $admin);
        } catch (\Exception $e) {
            Log::error('Account Edit Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            session()->flash('error', __('messages.customerNotFound'));
            return redirect()->route('management.admin');
        }
        return view('management.admin.edit', ['admin' => $admin]);
    }

    public function update(EditRequest $editRequest, $id)
    {
        try {
            DB::beginTransaction();
            $data = request()->all();
            $result = $this->adminRepo->update($id, $data);
            if (!empty($result)) {
                session()->flash('success', __('messages.accountUpdated'));
            }
            DB::commit();
        } catch (Exception $e) {
            Log::error('Update account Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            DB::rollBack();
            session()->flash('error', __('messages.updateFail'));
        }
        return redirect()->route('management.admin');
    }

    public function delete($id) {
        try {
            $this->adminRepo->delete($id);
            session()->flash('success', __('messages.accountDeleted'));
        } catch (Exception $e) {
            Log::error('Admin Delete Error ', ['admin' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            session()->flash('error', __('messages.deleteFail'));
        }

        return redirect()->route('management.admin');
    }
}

<?php

namespace App\Http\Controllers\Customer;

use App\Models\Report;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Http\Requests\Customer\UpdateProfileRequest;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class HomeController extends Controller
{
    public $customerRepo;
    public function __construct(CustomerRepositoryInterface $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    public function dashboard() {
        $customerId = Auth::guard('customer')->id();
        $customer = $this->customerRepo->find($customerId);
        $currentMonth = Carbon::now()->month;
        $reports = \App\Models\Report::withoutGlobalScopes()->join('accounts', 'accounts.id', '=', 'reports.account_id')
            ->join('customers', 'customers.id', '=', 'accounts.customer_id')
            ->whereRaw("MONTH(date) = $currentMonth")->where('accounts.del_flag', '=', config('const.active'))
            ->where('accounts.customer_id', $customerId)
            ->get();
        return view("customer.dashboard", ['customer' => $customer, 'reports' => $reports]);
    }

    public function profile() {
        $customer = Auth::guard('customer')->user();
        if (!empty($customer)){
            return view("customer.profile", ['customer' => $customer]);
        }
        return redirect()->route('customer.home', ['customer' => $customer]);
    }

    public function updateProfile(UpdateProfileRequest $updateProfileRequest)
    {
        try {
            DB::beginTransaction();
            $data = request()->all();
            if (request()->get('password') == null) {
                $data = Arr::except($data, 'password');
            }
            $result = $this->customerRepo->update($data['id'], $data);
            if (!empty($result)) {
                session()->flash('success', __('messages.customerUpdated'));
            }
            DB::commit();
        } catch (Exception $e) {
            dd($e);
            Log::error('Update customer Error ', ['admin_id' => Auth::guard('customer')->id(), 'error' => $e->getMessage()]);
            DB::rollBack();
            session()->flash('error', __('messages.updateFail'));
        }
        return redirect()->route('customer.profile');
    }
}

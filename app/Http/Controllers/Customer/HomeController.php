<?php

namespace App\Http\Controllers\Customer;

use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Http\Requests\Customer\UpdateProfileRequest;
use App\Http\Controllers\Controller;
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
        $customer = $this->customerRepo->find(Auth::guard('customer')->id());
        return view("customer.dashboard", ['customer' => $customer]);
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

<?php

namespace App\Http\Controllers;

use App\Repositories\Customer\CustomerRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\Customer\CreateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    protected $customerRepo;

    public function __construct(CustomerRepositoryInterface $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    public function index()
    {
        $customers = $this->customerRepo->search();
        return view('management.customer.index', ['customers' => $customers]);
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

    public function create() {
        return view('management.customer.create');
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

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = request()->all();
            if ($request->get('password') == null) {
                $data = Arr::except($data, 'password');
            }
            $result = $this->employeeRepo->update($id, $data);
            if (!empty($result)) {
                $this->welcomeMailService->sendMail($result);
                if (session()->has('tmp_url')) {
                    $id = $result->id;
                    $img_name = data_get(session('img_avatar'), 'avatar');
                    Storage::deleteDirectory(config('const.PATH_UPLOAD') . $id);
                    Storage::move(config('const.TEMP_DIR') . $img_name, config('const.PATH_UPLOAD') . $id . '/' . $img_name);
                    Storage::delete(session('tmp_src_avatar'));
                }
                session()->flash('success', __('messages.employeeUpdated'));
            }
            DB::commit();
        } catch (Exception $e) {
            Log::error('Update employee Error ', ['employee_id' => Auth::guard('employee')->id(), 'error' => $e->getMessage()]);
            DB::rollBack();
            session()->flash('error', __('messages.updateFail'));
        }

        session()->forget('tmp_src_avatar');
        session()->forget('tmp_url');

        return redirect()->route('employee.search');
    }
}

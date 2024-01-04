<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notpay\CreateRequest;
use App\Models\Account;
use App\Models\Admin;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\NotPay\NotPayRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotPayController extends Controller
{

    protected $customerRepo;
    protected $notpayRepo;

    public function __construct(NotPayRepositoryInterface $notpayRepo, CustomerRepositoryInterface $customerRepo)
    {
        $this->notpayRepo = $notpayRepo;
        $this->customerRepo = $customerRepo;
    }

    public function index()
    {
        $notpays = $this->notpayRepo->search();
        return view('management.notpay.index', ['notpays' => $notpays]);
    }

    public function create() {
        $techs = Admin::where('role', '2')->get();
        $customers = $this->customerRepo->getAll();
        return view('management.notpay.create', ['customers' => $customers, 'techs' => $techs]);
    }

    public function listAccount(Request $request)
    {
        if ($request->ajax()) {
            $output = '';
            $accounts = Account::where('customer_id', $request->customer_id)->get();
            if ($accounts) {
                foreach ($accounts as $key => $account) {
                    $output .= "<option value='$account->id'>$account->name</option>";
                }
            }

            return Response($output);
        }
    }

    public function store(CreateRequest $createRequest) {
        try {
            DB::beginTransaction();
            $data = request()->all();

            $this->notpayRepo->create($data);
            session()->flash('success', __('messages.notPayCreated'));
            DB::commit();
        } catch (\Exception $e) {
            Log::error('Not pay Create Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            DB::rollBack();
            session()->flash('error', __('messages.createFail'));
        }

        return redirect()->route('management.notpay');
    }
}

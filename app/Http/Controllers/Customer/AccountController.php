<?php

namespace App\Http\Controllers\Customer;

use App\Exports\AccountsExport;
use App\Repositories\Account\AccountRepositoryInterface;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

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
        $customer = $this->customerRepo->find(Auth::guard('customer')->id());
        $accounts = $this->accountRepo->search();
        return view('customer.account.index', ['accounts' => $accounts, 'customer' => $customer]);
    }

    public function show($id) {
        $account = $this->accountRepo->find($id);
        return view('customer.account.show', ['account' => $account]);
    }

    public function export(){
        $accounts = $this->accountRepo->getAll();
        return Excel::download(new AccountsExport, getCustomerName(Auth::guard('customer')->id()).' report.xlsx');
    }
}

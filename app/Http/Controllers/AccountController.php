<?php

namespace App\Http\Controllers;

use App\Repositories\Account\AccountRepositoryInterface;
use App\Repositories\Customer\CustomerRepositoryInterface;
use Illuminate\Http\Request;

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
}

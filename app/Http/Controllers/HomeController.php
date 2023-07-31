<?php

namespace App\Http\Controllers;

use App\Repositories\Customer\CustomerRepositoryInterface;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $customerRepo;

    public function __construct(CustomerRepositoryInterface $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    public function dashboard() {
        $customers = $this->customerRepo->search();
        return view("management.dashboard", ['customers' => $customers]);
    }
}

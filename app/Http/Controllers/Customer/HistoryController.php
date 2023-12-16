<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\History\EditRequest;
use App\Http\Requests\History\CreateRequest;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\History\HistoryRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class HistoryController extends Controller
{
    protected $historyRepo;
    protected $customerRepo;

    public function __construct(HistoryRepositoryInterface $historyRepo, CustomerRepositoryInterface $customerRepo)
    {
        $this->historyRepo = $historyRepo;
        $this->customerRepo = $customerRepo;
    }

    public function index()
    {
        $histories = $this->historyRepo->search();
        dd($histories);
        return view('management.history.index', ['histories' => $histories]);
    }
}

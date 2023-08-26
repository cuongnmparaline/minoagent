<?php

namespace App\Http\Controllers;

use App\Http\Requests\History\EditRequest;
use App\Http\Requests\History\CreateRequest;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\History\HistoryRepositoryInterface;
use Illuminate\Http\Request;
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
        $customers = $this->customerRepo->getAll();
        return view('management.history.index', ['histories' => $histories, 'customers' => $customers]);
    }

    public function create() {
        $customers = $this->customerRepo->getAll();
        return view('management.history.create', ['customers' => $customers]);
    }

    public function store(CreateRequest $createRequest) {
        try {
            DB::beginTransaction();
            $data = request()->all();
            $this->historyRepo->create($data);
            $customer = $this->customerRepo->find($data['customer_id']);
            $this->customerRepo->update($customer->id, ['balance' => $customer->balance+$data['amount']]);
            session()->flash('success', __('messages.historyCreated'));
            DB::commit();
        } catch (\Exception $e) {
            Log::error('History Create Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            DB::rollBack();
            session()->flash('error', __('messages.createFail'));
        }

        return redirect()->route('management.history');
    }

    public function edit($id) {
        try {
            $history = $this->historyRepo->find($id);
            $customers = $this->customerRepo->getAll();
            session()->put('history_data', $history);
        } catch (\Exception $e) {
            Log::error('Customer Edit Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            session()->flash('error', __('messages.customerNotFound'));
            return redirect()->route('management.customer');
        }
        return view('management.history.edit', ['history' => $history, 'customers' => $customers]);
    }

    public function update(EditRequest $editRequest, $id)
    {
        try {
            DB::beginTransaction();
            $data = request()->all();
            if (!empty($data['addAmount']))  {
                $data['amount'] += $data['addAmount'];
            }
            $result = $this->historyRepo->update($id, $data);
            $customer = $this->customerRepo->find($data['customer_id']);
            $this->customerRepo->update($customer->id, ['balance' => $customer->balance+$data['addAmount']]);
            if (!empty($result)) {
                session()->flash('success', __('messages.historyUpdated'));
            }
            DB::commit();
        } catch (Exception $e) {
            Log::error('History account Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            DB::rollBack();
            session()->flash('error', __('messages.updateFail'));
        }
        return redirect()->route('management.history');
    }

    public function delete($id) {
        try {
            $this->historyRepo->delete($id);
            session()->flash('success', __('messages.historyDeleted'));
        } catch (Exception $e) {
            Log::error('History Delete Error ', ['admin' => Auth::guard('admin')->id(), 'error' => $e->getMessage()]);
            session()->flash('error', __('messages.deleteFail'));
        }

        return redirect()->route('management.history');
    }
}

<?php
namespace App\Repositories\Account;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Report;
use App\Repositories\BaseRepository;
use App\Repositories\Report\ReportRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountRepository extends BaseRepository implements AccountRepositoryInterface
{

    private $reportRepo;

    public function __construct(ReportRepositoryInterface $reportRepo)
    {
        $this->reportRepo = $reportRepo;
        parent::__construct();
    }

    public function getModel()
    {
        return \App\Models\Account::class;
    }

    public function search($paginate = true)
    {
        $result = $this->model->select('accounts.id', 'accounts.name', 'code', 'customer_id', 'status', 'limit');

        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role == 2) {
            $result->withoutGlobalScopes()->join('customers', 'customers.id', '=', 'accounts.customer_id')->where('admin_id', Auth::guard('admin')->id())->where('accounts.del_flag', config('const.active'));
        }

        if (!blank(request()->get('status'))) {
            $result->where('status', request()->get('status'));
        }

        if (Auth::guard('customer')->check()) {
            $result->where('customer_id', Auth::guard('customer')->id());
        }

        if (request()->get('name')) {
            $result->where('name', 'like', '%' . request()->get('name') . '%');
        }
        if (request()->get('code')) {
            $result->where('code', 'like', '%' . request()->get('code') . '%');
        }
        if (request()->get('date')) {
            if (Auth::guard('customer')->check()) {
                $result = \App\Models\Account::withoutGlobalScopes()->select('accounts.id', 'accounts.name', 'accounts.code', 'accounts.customer_id', 'accounts.status', 'accounts.limit', 'reports.date', 'reports.amount', 'reports.amount_fee')->join('reports', 'reports.account_id', '=', 'accounts.id')->where('reports.date', '=', request()->get('date'))->where('accounts.del_flag', '=', config('const.active'))->where('accounts.customer_id', Auth::guard('customer')->id());
            } else {
                $result = \App\Models\Account::withoutGlobalScopes()->select('accounts.id', 'accounts.name', 'accounts.code', 'accounts.customer_id', 'accounts.status', 'accounts.limit', 'reports.date', 'reports.amount', 'reports.amount_fee')->join('reports', 'reports.account_id', '=', 'accounts.id')->where('reports.date', '=', request()->get('date'))->where('accounts.del_flag', '=', config('const.active'));
            }
        }
        $customerId = '';
        if (request()->get('customer')) {
            $customer = Customer::where('name', request()->get('customer'))->get()->first();
            if ($customer) {
                $customerId = $customer->id;
            }
            $result->where('customer_id', '=', $customerId);
        }


        if ($paginate == false){
            return $result->get();
        }
        return $result->paginate(config('const.numPerPage'));
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $this->update($id, ['del_flag' => config('const.deleted')]);
            $reports = Report::where('account_id', $id)->get();
            foreach ($reports as $report) {
                $this->reportRepo->delete($report->id);
            }
            DB::commit();
        } catch (\Exception $ex){
            dd($ex->getMessage());
            DB::rollBack();
        }
    }
}

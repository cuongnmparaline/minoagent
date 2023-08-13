<?php
namespace App\Repositories\History;

use App\Models\Customer;
use App\Repositories\BaseRepository;
use App\Repositories\Report\ReportRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class HistoryRepository extends BaseRepository implements HistoryRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\History::class;
    }

    public function search($paginate = true)
    {
        $result = $this->model->select('id', 'customer_id', 'date', 'amount', 'hashcode');
//        if (Auth::guard('customer')->check()) {
//            $result->where('customer_id', Auth::guard('customer')->id());
//        }
//        if (request()->get('name')) {
//            $result->where('name', 'like', '%' . request()->get('name') . '%');
//        }
//        if (request()->get('code')) {
//            $result->where('code', 'like', '%' . request()->get('code') . '%');
//        }
//        if (request()->get('date')) {
////            $result->where('date', request()->get('date'));
//            $result = \App\Models\Account::withoutGlobalScopes()->select('accounts.id', 'accounts.name', 'accounts.code', 'accounts.customer_id', 'accounts.status')->join('reports', 'reports.account_id', '=', 'accounts.id')->where('reports.date', '=', request()->get('date'))->where('accounts.del_flag', '=', config('const.active'));
//
//        }
//        $customerId = '';
//        if (request()->get('customer')) {
//            $customer = Customer::where('name', request()->get('customer'))->get()->first();
//            if ($customer) {
//                $customerId = $customer->id;
//            }
//            $result->where('customer_id', '=', $customerId);
//        }


        if ($paginate == false){
            return $result->get();
        }
        return $result->paginate(config('const.numPerPage'));
    }
}

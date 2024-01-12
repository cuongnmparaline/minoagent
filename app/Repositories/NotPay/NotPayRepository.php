<?php
namespace App\Repositories\NotPay;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Report;
use App\Repositories\BaseRepository;
use App\Repositories\Report\ReportRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotPayRepository extends BaseRepository implements NotPayRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\NotPay::class;
    }

    public function search($paginate = true)
    {
        $result = $this->model->select('not_pay.id', 'customer_id', 'account_id', 'amount', 'link_image');

        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->role != 1) {
            $result->withoutGlobalScopes()->join('customers', 'customers.id', '=', 'not_pay.customer_id')->where('customers.admin_id', Auth::guard('admin')->user()->role)->where('not_pay.del_flag', config('const.active'));
        }
//        if (request()->get('customer_id')) {
//            $result->where('customer_id', 'like', '%' . request()->get('customer_id') . '%');
//        }
//        if (request()->get('account_id')) {
//            $result->where('code', 'like', '%' . request()->get('code') . '%');
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

<?php
namespace App\Repositories\Account;

use App\Models\Customer;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class AccountRepository extends BaseRepository implements AccountRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\Account::class;
    }

    public function search($paginate = true)
    {
        $result = $this->model->select('id', 'name', 'code', 'customer_id', 'status');
        if (Auth::guard('customer')->check()) {
            $result->where('customer_id', Auth::guard('customer')->id());
        }

        if (request()->get('name')) {
            $result->where('name', 'like', '%' . request()->get('name') . '%');
        }
        if (request()->get('code')) {
            $result->where('code', 'like', '%' . request()->get('code') . '%');
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
}

<?php
namespace App\Repositories\Account;

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
            $result->where('first_name', 'like', '%' . request()->get('name') . '%');
        }
        if (request()->get('code')) {
            $result->where('email', 'like', '%' . request()->get('searchEmail') . '%');
        }
        if ($paginate == false){
            return $result->get();
        }
        return $result->paginate(config('const.numPerPage'));
    }
}

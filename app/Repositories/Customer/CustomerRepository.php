<?php
namespace App\Repositories\Customer;

use App\Repositories\Account\AccountRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\History\HistoryRepositoryInterface;
use App\Repositories\Report\ReportRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\Customer::class;
    }

    public function getByAdmin() {
        return $this->model->select('id', 'name', 'email', 'balance', 'fee', 'ins_datetime', 'admin_id')->where('admin_id', Auth::guard('admin')->user()->id)->get();
    }

    public function search($paginate = true)
    {
        $result = $this->model->select('id', 'name', 'email', 'balance', 'fee', 'ins_datetime', 'admin_id');

        if (Auth::guard('admin')->user()->role != 1) {
            $result->where('admin_id', Auth::guard('admin')->id());
        }

        if (request()->get('searchName')) {
            $result->where('first_name', 'like', '%' . request()->get('name') . '%');
        }
        if (request()->get('email')) {
            $result->where('email', 'like', '%' . request()->get('searchEmail') . '%');
        }
        if ($paginate == false){
            return $result->get();
        }
        return $result->paginate(config('const.numPerPage'));
    }
}

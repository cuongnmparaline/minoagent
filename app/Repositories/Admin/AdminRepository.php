<?php
namespace App\Repositories\Admin;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Report;
use App\Repositories\BaseRepository;
use App\Repositories\Report\ReportRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminRepository extends BaseRepository implements AdminRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\Admin::class;
    }

    public function search($paginate = true)
    {
        $result = $this->model->select('id', 'name', 'email', 'password', 'role');


        if ($paginate == false){
            return $result->get();
        }
        return $result->paginate(config('const.numPerPage'));
    }
}

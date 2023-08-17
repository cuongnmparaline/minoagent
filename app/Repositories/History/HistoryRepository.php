<?php
namespace App\Repositories\History;

use App\Models\Customer;
use App\Repositories\BaseRepository;
use App\Repositories\Customer\CustomerRepository;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Repositories\Report\ReportRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HistoryRepository extends BaseRepository implements HistoryRepositoryInterface
{

    public function getModel()
    {
        return \App\Models\History::class;
    }

    public function search($paginate = true)
    {
        $result = $this->model->select('id', 'customer_id', 'date', 'amount', 'hashcode');
        if (Auth::guard('customer')->check()) {
            $result->where('customer_id', Auth::guard('customer')->id());
        }

        if ($paginate == false){
            return $result->get();
        }
        return $result->paginate(config('const.numPerPage'));
    }
}

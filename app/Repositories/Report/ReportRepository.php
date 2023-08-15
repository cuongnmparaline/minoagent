<?php
namespace App\Repositories\Report;

use App\Models\Account;
use App\Models\Customer;
use App\Repositories\BaseRepository;

class ReportRepository extends BaseRepository implements ReportRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\Report::class;
    }

    public function search($paginate = true)
    {
        $result = $this->model->select('id', 'account_id', 'date', 'currency', 'unpaid', 'amount', 'amount_fee')->sortable(['id' => 'desc']);

//       dd(request()->all());
        $accountId = '';
        if (request()->get('searchCode')) {
            $account = Account::where('code', 'like', '%' . request()->get('searchCode') . '%')->get()->first();
            if ($account) {
                $accountId = $account->id;
            }
            $result->where('account_id', '=', $accountId);
        }
        if (request()->get('searchName')) {
            $account = Account::where('name', 'like', '%' . request()->get('searchName') . '%')->get()->first();
            if ($account) {
                $accountId = $account->id;
            }
            $result->where('account_id', '=', $accountId);
        }
        if (request()->get('searchDate')) {
            $result->where('date', request()->get('searchDate'));
        }
        if ($paginate == false){
            return $result->get();
        }
        return $result->paginate(config('const.numPerPage'));
    }
}

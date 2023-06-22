<?php
namespace App\Repositories\Report;

use App\Repositories\BaseRepository;

class ReportRepository extends BaseRepository implements ReportRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\Report::class;
    }

    public function search($paginate = true)
    {
        $result = $this->model->select('id', 'account_id', 'date', 'currency', 'unpaid', 'amount')->sortable(['id' => 'desc']);
        if ($paginate == false){
            return $result->get();
        }
        return $result->paginate(config('const.numPerPage'));
    }
}

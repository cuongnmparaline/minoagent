<?php
namespace App\Repositories\Customer;

use App\Repositories\BaseRepository;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\Customer::class;
    }

    public function search($paginate = true)
    {
        $result = $this->model->select('id', 'name', 'email', 'balance', 'fee');
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

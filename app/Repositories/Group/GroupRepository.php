<?php
namespace App\Repositories\Group;

use App\Repositories\BaseRepository;

class GroupRepository extends BaseRepository implements GroupRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\Group::class;
    }

    public function search($paginate = true)
    {
        $result = $this->model->select('id', 'name', 'customer_id');
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

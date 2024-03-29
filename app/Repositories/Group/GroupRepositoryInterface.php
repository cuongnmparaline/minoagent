<?php
namespace App\Repositories\Group;

use App\Repositories\RepositoryInterface;

interface GroupRepositoryInterface extends RepositoryInterface
{
    public function search($paginate = true);
}

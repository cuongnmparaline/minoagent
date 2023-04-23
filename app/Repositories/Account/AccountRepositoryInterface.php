<?php
namespace App\Repositories\Account;

use App\Repositories\RepositoryInterface;

interface AccountRepositoryInterface extends RepositoryInterface
{
    public function search($paginate = true);
}

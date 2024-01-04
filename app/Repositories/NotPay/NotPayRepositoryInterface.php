<?php
namespace App\Repositories\NotPay;

use App\Repositories\RepositoryInterface;

interface NotPayRepositoryInterface extends RepositoryInterface
{
    public function search($paginate = true);
}

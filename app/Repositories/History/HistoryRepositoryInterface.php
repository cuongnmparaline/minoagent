<?php
namespace App\Repositories\History;

use App\Repositories\RepositoryInterface;

interface HistoryRepositoryInterface extends RepositoryInterface
{
    public function search($paginate = true);
}

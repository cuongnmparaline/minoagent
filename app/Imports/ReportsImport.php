<?php

namespace App\Imports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ReportsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Report([
            'account_id' => $row['account_id'],
            'date' => $row['date'],
            'amount' => $row['amount'],
            'currency' => $row['currency'],
        ]);
    }
}

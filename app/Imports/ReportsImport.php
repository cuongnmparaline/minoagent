<?php

namespace App\Imports;

use App\Models\Report;
use App\Repositories\Account\AccountRepository;
use App\Repositories\Report\ReportRepository;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Account;

class ReportsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */


    public function model(array $row)
    {
        $accountRepo = app((AccountRepository::class));
        $account = Account::where('code', substr($row['account_code'], strpos($row['account_code'], "_") + 1))->get()->first();
        if (!empty($account)) {

            ;

            $report = app(ReportRepository::class)->create([
                'account_id' => $account->id,
                'date' => Carbon::now(),
                'amount' => $row['spend'],
                'currency' => substr($row['currency'], 0, strpos($row['currency'], "_")),
            ]);
        } else {
            $account = $accountRepo->create([
                'code' => substr($row['account_code'], strpos($row['account_code'], "_") + 1),
                'name' => $row['account_code'],
                'customer_id' => 1,
                'status' => $this->getStatus($row['status']),
            ]);

            $report = app(ReportRepository::class)->create([
                'account_id' => $account->id,
                'date' => Carbon::now(),
                'amount' => $row['spend'],
                'currency' => substr($row['currency'], 0, strpos($row['currency'], "_")),
            ]);
        }

        return $report;
    }

    function getStatus($status)
    {
        switch ($status) {
            case config('const.status.0'):
                return 0;
            case config('const.status.1'):
                return 1;
            case config('const.status.2'):
                return 2;
        }
    }
}

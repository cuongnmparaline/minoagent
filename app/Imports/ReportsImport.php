<?php

namespace App\Imports;

use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Models\Report;
use App\Repositories\Account\AccountRepository;
use App\Repositories\Report\ReportRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Account;
use function Ramsey\Collection\Map\get;

class ReportsImport implements ToModel, WithHeadingRow, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function __construct($date, $currencies)
    {
        $this->date = $date;
        $this->currencies = $currencies;
    }

    public function model(array $row)
    {
        $accountRepo = app((AccountRepository::class));
        $account = Account::where('code', substr($row['account_code'], strpos($row['account_code'], "_") + 1))->get()->first();
        if($row['limit'] == 'No limit') {
            $row['limit'] = 0;
        }
        try {
            $currency = substr($row['currency'], 0, strpos($row['currency'], "_"));
            $unpaid = number_format($row['unpaid'] / $this->currencies[$currency], 2);
            $amount = number_format($row['spend'] / $this->currencies[$currency], 2);
            if (!empty($account)) {
                $report = Report::where([
                    'account_id' => $account->id,
                    'date' => Carbon::now()->format('Y-m-d')
                ])->first();

                if ($report !== null) {
                    $oldAmount = $report->getOriginal('amount');
                    $report->update(['amount' => $amount, 'upd_datetime' => date('Y-m-d H:i:s'),
                        'upd_id' => Auth::guard('admin')->id()]);
                    $balance = $report->account->customer->balance;
                    $report->account->customer->update(['balance' => $balance - ($amount - $oldAmount)]);
                } else {
                    $report = Report::create([
                        'account_id' => $account->id,
                        'date' => $this->date,
                        'unpaid' => $unpaid,
                        'amount' => $amount,
                        'currency' => $currency,
                        'limit' => $row['limit'],
                        'ins_datetime' => date('Y-m-d H:i:s'),
                        'ins_id' => Auth::guard('admin')->id()
                    ]);
                }
            } else {
                $account = $accountRepo->create([
                    'code' => substr($row['account_code'], strpos($row['account_code'], "_") + 1),
                    'name' => $row['account_name'],
                    'customer_id' => 1,
                    'status' => $this->getStatus($row['status']),
                    'limit' => $row['limit']
                ]);
                $report = Report::where([
                    'account_id' => $account->id,
                    'date' => Carbon::now()->format('Y-m-d')
                ])->first();

                if ($report !== null) {
                    $oldAmount = $report->getOriginal('amount');
                    $report->update(['amount' => $amount, 'upd_datetime' => date('Y-m-d H:i:s'),
                        'upd_id' => Auth::guard('admin')->id()]);
                    $balance = $report->account->customer->balance;
                    $report->account->customer->update(['balance' => $balance - ($amount - $oldAmount)]);
                } else {
                    $report = Report::create([
                        'account_id' => $account->id,
                        'date' => $this->date,
                        'unpaid' => $unpaid,
                        'amount' => $amount,
                        'currency' => $currency,
                        'limit' => $row['limit'],
                        'ins_datetime' => date('Y-m-d H:i:s'),
                        'ins_id' => Auth::guard('admin')->id()
                    ]);
                }
            }
            return $account;
        } catch (\Exception $exception){
            Log::error('Report Create Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $exception->getMessage()]);
            return $account;
        }
    }

    public function chunkSize(): int
    {
        return 1000;
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

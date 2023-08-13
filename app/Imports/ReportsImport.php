<?php

namespace App\Imports;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Report;
use App\Repositories\History\AccountRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ReportsImport implements ToCollection, WithHeadingRow, WithChunkReading, WithValidation
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

    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            Log::error($row['account_name']);
            $accountRepo = app((AccountRepository::class));
            $account = Account::where('code', substr($row['account_code'], strpos($row['account_code'], "_") + 1))->get()->first();
            if($row['limit'] == 'No limit') {
                $row['limit'] = 0;
            }
            $customerName = substr($row['account_name'], 0, strpos($row['account_name'], "_"));
            $haveCustomer = Customer::where('name', $customerName)->get()->first();

            if($haveCustomer){
                try {
                    $currency = substr($row['currency'], 0, strpos($row['currency'], "_"));
                    (double)$unpaid = $row['unpaid'] / $this->currencies[$currency];
                    (double)$amount = $row['spend'] / $this->currencies[$currency];
                    if (!empty($account)) {
                        $report = Report::where([
                            'account_id' => $account->id,
                            'date' => $this->date,
                        ])->first();
                        if (!empty($report)) {
                            $oldAmount = $report->getOriginal('amount');
                            $report->update(['amount' => $amount, 'upd_datetime' => date('Y-m-d H:i:s'),
                                'upd_id' => Auth::guard('admin')->id()]);
                            $newAmount = $amount - $oldAmount;
                            $haveCustomer->update(['balance' => $haveCustomer->balance - ($newAmount + ($newAmount * ($haveCustomer->fee / 100)))]);
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
                            $haveCustomer->update(['balance' => $haveCustomer->balance - ($amount + $amount * ($haveCustomer->fee / 100))]);
                        }
                    } else {
                        $account = $accountRepo->create([
                            'code' => substr($row['account_code'], strpos($row['account_code'], "_") + 1),
                            'name' => $row['account_name'],
                            'customer_id' => $haveCustomer->id,
                            'status' => $this->getStatus($row['status']),
                            'limit' => $row['limit']
                        ]);
                        $report = Report::where([
                            'account_id' => $account->id,
                            'date' => $this->date,
                        ])->first();

                        if ($report !== null) {
                            $oldAmount = $report->getOriginal('amount');
                            $report->update(['amount' => $amount, 'upd_datetime' => date('Y-m-d H:i:s'),
                                'upd_id' => Auth::guard('admin')->id()]);
                            $newAmount = $amount - $oldAmount;
                            $haveCustomer->update(['balance' => $haveCustomer->balance - ($newAmount + ($newAmount * ($haveCustomer->fee / 100)))]);
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
                            $haveCustomer->update(['balance' => $haveCustomer->balance - ($amount + $amount * ($haveCustomer->fee / 100))]);
                        }
                    }
                } catch (\Exception $exception){
                    Log::error('Report Create Error ', ['admin_id' => Auth::guard('admin')->id(), 'error' => $exception->getMessage()]);
                }
            }
        }
    }

    public function prepareForValidation($data, $index)
    {
        $data['customer_name'] = substr($data['account_name'], 0, strpos($data['account_name'], "_"));

        return $data;
    }

    public function customValidationMessages()
    {
        return [
            'customer_name.exists' => 'Chưa có customer ở hàng số :attribute',
        ];
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'exists:customers,name',
        ];
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
            default:
                return 0;
        }
    }
}

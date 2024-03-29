<?php

namespace App\Imports;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Report;
use App\Repositories\Account\AccountRepository;
use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use Carbon\Carbon;
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

    public function __construct($date, $isCalculate)
    {
        $this->date = $date;
        $this->isCalculate = $isCalculate;
        $this->exchangeRates = app(ExchangeRate::class);

    }

    public function collection(Collection $rows)
    {
        $rates = $this->exchangeRates->exchangeRate(
            'USD',
            config('const.currency'),
        );
        foreach ($rows as $row)
        {

            $accountRepo = app((AccountRepository::class));
            $account = Account::where('name', $row['account_name'])->get()->first();
            if($row['limit'] == 'No limit') {
                $row['limit'] = 0;
            }

            $customerName = substr($row['account_name'], 0, strpos($row['account_name'], "_"));
            $haveCustomer = Customer::where('name', $customerName)->get()->first();

            if($haveCustomer){
                try {
                    $currency = substr($row['currency'], 0, strpos($row['currency'], "_"));
                    (double)$amount = $row['spend'] / $rates[$currency];
                    (double)$unpaid = $row['unpaid'] / $rates[$currency];

                    if (!empty($account)) {
                        if ($row['status'] == 'Disabled') {
                            $account->update(['status' => '1']);
                        }
                        $report = Report::where([
                            'account_id' => $account->id,
                            'date' => $this->date,
                        ])->first();

                        $today = Carbon::createFromFormat('Y-m-d',  $this->date);
                        $yesterday = $today->subDay();
                        $yesterdayReport = Report::where([
                            'account_id' => $account->id,
                            'date' => $yesterday->format('Y-m-d'),
                        ])->first();

                        if (!empty($yesterdayReport && !empty($this->isCalculate))) {
                            $realSpend = $yesterdayReport->unpaid + $yesterdayReport->amount + $amount - $unpaid;
                            $yesterdayReport->update(['real_spend' => $realSpend]);
                        }

                        if (!empty($report)) {
                            $oldAmount = $report->getOriginal('amount');

                            $update = ['amount' => $amount, 'upd_datetime' => date('Y-m-d H:i:s'),
                                'upd_id' => Auth::guard('admin')->id()];

                            if (!empty($this->isCalculate)) {
                                $update = array_merge($update, ['unpaid' => $unpaid]);
                            }

                            $report->update($update);
                            $newAmount = $amount - $oldAmount;

                            $report->update([
                                'amount' => $amount,
                                'amount_fee' =>$amount * ($haveCustomer->fee / 100),
                            ]);

                            $haveCustomer->update(['balance' => $haveCustomer->balance - ($newAmount + ($newAmount * ($haveCustomer->fee / 100)))]);
                        } else {
                            $amountFee = $amount * ($haveCustomer->fee / 100);
                            $report = Report::create([
                                'account_id' => $account->id,
                                'date' => $this->date,
                                'unpaid' => $unpaid,
                                'amount' => $amount,
                                'amount_fee' => $amountFee,
                                'currency' => $currency,
                                'limit' => $row['limit'],
                                'ins_datetime' => date('Y-m-d H:i:s'),
                                'ins_id' => Auth::guard('admin')->id()
                            ]);
                            $haveCustomer->update(['balance' => $haveCustomer->balance - ($amount + $amountFee)]);
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
                            $report->update([
                                'amount' => $amount,
                                'amount_fee' =>$amount * ($haveCustomer->fee / 100),
                            ]);
                            $haveCustomer->update(['balance' => $haveCustomer->balance - ($newAmount + ($newAmount * ($haveCustomer->fee / 100)))]);
                        } else {
                            $amountFee = $amount * ($haveCustomer->fee / 100);
                            $report = Report::create([
                                'account_id' => $account->id,
                                'date' => $this->date,
                                'unpaid' => $unpaid,
                                'amount' => $amount,
                                'amount_fee' => $amountFee,
                                'currency' => $currency,
                                'limit' => $row['limit'],
                                'ins_datetime' => date('Y-m-d H:i:s'),
                                'ins_id' => Auth::guard('admin')->id()
                            ]);
                            $haveCustomer->update(['balance' => $haveCustomer->balance - ($amount + $amountFee)]);
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

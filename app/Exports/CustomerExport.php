<?php

namespace App\Exports;

use App\Exports;
use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomerExport implements FromView
{
    public function view(): View
    {
        return view('management.customer.export', [
            'customers' => Customer::all()
        ]);
    }
}

<?php

namespace App\Exports;

use App\Exports;
use App\Models\Account;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AccountsExport implements FromView
{
    public function view(): View
    {
        return view('customer.export.accounts', [
            'accounts' => Account::all()
        ]);
    }
}

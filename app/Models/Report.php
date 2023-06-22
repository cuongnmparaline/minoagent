<?php

namespace App\Models;

use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Scopes\DelFlagScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Report extends Model
{
    use HasFactory, Sortable;

    protected $table = 'reports';

    protected $fillable = [
        'id',
        'account_id',
        'date',
        'unpaid',
        'amount',
        'currency',
        'ins_id',
        'upd_id',
        'ins_datetime',
        'upd_datetime',
        'del_flag'
    ];

    public $sortable = ['id', 'amount', 'date', 'unpaid'];

    protected static function booted()
    {
        static::addGlobalScope(new DelFlagScope());
    }

//    public function getAmountAttribute()
//    {
//        return Currency::convert()
//            ->from($this->attributes['currency'])
//            ->to('USD')
//            ->amount($this->attributes['amount'])
//            ->get();
//    }

    public $timestamps = false;

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}

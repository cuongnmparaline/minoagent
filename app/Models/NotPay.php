<?php

namespace App\Models;

use App\Scopes\DelFlagScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotPay extends Model
{
    use HasFactory;

    protected $table = 'not_pay';

    protected $fillable = [
        'id',
        'account_id',
        'customer_id',
        'amount',
        'link_image',
        'ins_id',
        'upd_id',
        'ins_datetime',
        'upd_datetime',
        'del_flag'
    ];

    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope(new DelFlagScope());
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}

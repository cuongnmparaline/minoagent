<?php

namespace App\Models;

use App\Scopes\DelFlagScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'id',
        'account_id',
        'date',
        'amount',
        'currency',
        'ins_id',
        'upd_id',
        'ins_datetime',
        'upd_datetime',
        'del_flag'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new DelFlagScope());
    }

    public $timestamps = false;

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}

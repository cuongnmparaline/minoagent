<?php

namespace App\Models;

use App\Scopes\DelFlagScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Group  extends Authenticatable
{
    use HasFactory;

    protected $table = 'groups';

    protected $fillable = [
        'id',
        'name',
        'customer_id',
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
}

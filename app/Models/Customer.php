<?php

namespace App\Models;

use App\Scopes\DelFlagScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'id',
        'email',
        'name',
        'nick_name',
        'balance',
        'fee',
        'password',
        'ins_id',
        'upd_id',
        'ins_datetime',
        'upd_datetime',
        'del_flag'
    ];

    protected $hidden = [
        'password',
    ];

    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope(new DelFlagScope());
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}

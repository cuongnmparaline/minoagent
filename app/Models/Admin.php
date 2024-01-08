<?php

namespace App\Models;

use App\Scopes\DelFlagScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory;

    protected $table = 'admins';

    protected $fillable = [
        'id',
        'email',
        'name',
        'password',
        'role',
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

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
    protected static function booted()
    {
        static::addGlobalScope(new DelFlagScope());
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

}

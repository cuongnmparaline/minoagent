<?php

namespace App\Models;

use App\Scopes\DelFlagScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class History extends Model
{
    use HasFactory, Sortable;

    protected $table = 'histories';

    protected $fillable = [
        'id',
        'customer_id',
        'date',
        'amount',
        'hashcode',
        'ins_id',
        'upd_id',
        'ins_datetime',
        'upd_datetime',
        'del_flag'
    ];

    public $timestamps = false;

    public $sortable = ['date', 'amount'];

    protected static function booted()
    {
        static::addGlobalScope(new DelFlagScope());
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

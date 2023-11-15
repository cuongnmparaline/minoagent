<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostHasCategories extends Model
{
    use HasFactory;
    
    protected $table = 'post_has_categories';
    protected $with = ['post_categories'];

    protected $fillable = [
        'id',
        'post_id',
        'post_categories_id',
    ];

    public function post_categories()
    {
        return $this->belongsToMany(PostCategories::class, $this->table, 'id', 'post_categories_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    
    protected $table = 'posts';
    protected $with = [
        'post_categories',
        'author',
        'status'
    ];

    protected $fillable = [
        'id', 
        'title', 
        'summary', 
        'status_id', 
        'content', 
        'image', 
        'user_id'
    ];

    public function post_categories() {
        return $this->hasMany(PostHasCategories::class, 'post_id');
    }

    public function author()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }

    public function status()
    {
        return $this->belongsTo(PostStatus::class, 'status_id');
    }
}

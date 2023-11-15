<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCategories extends Model
{
    use HasFactory;
    
    protected $table = 'post_categories';
    protected $with = ['author'];

    protected $fillable = [
        'id',
        'name',
        'description',
        'status',
        'author_id',
    ];

    public const ACTIVE = 'ACTIVE';
    public const DEACTIVE = 'DEACTIVE';

    public function author()
    {
        return $this->belongsTo(Admin::class, 'author_id');
    }
}

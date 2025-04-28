<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "alias",
        "description",
        "is_active"
    ];

    protected $primaryKey = 'id';
    protected $table = 'blog_categories';

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'blog_category_id');
    }
}

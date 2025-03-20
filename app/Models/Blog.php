<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        "title",
        "alias",
        "image",
        "abstract",
        "content",
        "blog_category_id",
        "user_id",
        "is_active"
    ];

    protected $primaryKey = 'id';
    protected $table = 'blogs';

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

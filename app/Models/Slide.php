<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;
    protected $fillable = [
        "title",
        "sub_title",
        "alias",
        "image",
        "order",
        "is_active",
        "description"
    ];

    protected $primaryKey = 'id';
    protected $table = 'slides';
    public $timestamps = false;
}

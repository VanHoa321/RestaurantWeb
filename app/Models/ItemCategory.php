<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "is_active",
        "description"
    ];

    protected $primaryKey = 'id';
    protected $table = 'item_categories';
    public $timestamps = false;
}

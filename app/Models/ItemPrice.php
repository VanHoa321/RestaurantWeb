<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPrice extends Model
{
    use HasFactory;
    protected $fillable = [
        "item_id",
        "name",
        "sale_price",
        "code_price",
        "is_active",
        "description"
    ];
    protected $primaryKey = 'id';
    protected $table = 'item_prices';
}

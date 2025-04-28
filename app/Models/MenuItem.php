<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;
    protected $fillable = [
        "menu_id",
        "item_id",
        "description"
    ];

    protected $primaryKey = 'id';
    protected $table = 'menu_items';

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}

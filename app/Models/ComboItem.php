<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboItem extends Model
{
    use HasFactory;
    protected $fillable = [
        "combo_id",
        "item_id",
        "quantity"
    ];

    protected $primaryKey = 'id';
    protected $table = 'combo_items';
    public $timestamps = false;

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "unit_id",
        "category_id",
        "avatar",
        "is_active",
        "description"
    ];
    
    protected $primaryKey = 'id';
    protected $table = 'items';

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }

    public function activePrice()
    {
        return $this->hasOne(ItemPrice::class)->where('is_active', 1);
    }
}

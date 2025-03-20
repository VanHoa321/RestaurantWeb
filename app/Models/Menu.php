<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "order_menu",
        "avatar",
        "is_active"
    ];

    protected $primaryKey = 'id';
    protected $table = 'menus';

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'menu_id');
    }
}

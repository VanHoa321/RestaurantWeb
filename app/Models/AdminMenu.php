<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminMenu extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "level",
        "parent",
        "order",
        "icon",
        "route",
        "is_active"
    ];
    
    protected $primaryKey = 'id';
    protected $table = 'admin_menus';
    public $timestamps = false;

    public function parents()
    {
        return $this->belongsTo(AdminMenu::class, 'parent');
    }
}

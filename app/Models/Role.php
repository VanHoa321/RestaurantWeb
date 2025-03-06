<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        "group_id",
        "admin_menu_id"
    ];

    protected $primaryKey = 'id';
    protected $table = 'roles';
    public $timestamps = false;

    public function menu()
    {
        return $this->belongsTo(AdminMenu::class, 'admin_menu_id');
    }
}

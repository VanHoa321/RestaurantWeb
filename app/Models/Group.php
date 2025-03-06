<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "description"
    ];

    protected $primaryKey = 'id';
    protected $table = 'groups';
    public $timestamps = false;

    public function roles()
    {
        return $this->hasMany(Role::class, 'group_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "address",
        "phone",
        "email",
        "is_active",
        "description"
    ];

    protected $primaryKey = 'id';
    protected $table = 'branchs';
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableType extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "max_seats",
        "is_active",
        "description"
    ];

    protected $primaryKey = 'id';
    protected $table = 'table_types';
    public $timestamps = false;
}

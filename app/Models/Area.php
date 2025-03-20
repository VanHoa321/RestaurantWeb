<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "branch_id",
        "is_active",
        "description"
    ];

    protected $primaryKey = 'id';
    protected $table = 'areas';
    public $timestamps = false;

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}

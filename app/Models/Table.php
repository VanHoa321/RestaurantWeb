<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;
    use HasFactory;
    protected $fillable = [
        "name",
        "area_id",
        "type_id",
        "status",
        "is_active",
        "description"
    ];
    protected $primaryKey = 'id';
    protected $table = 'tables';

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function type()
    {
        return $this->belongsTo(TableType::class, 'type_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'table_id');
    }
}

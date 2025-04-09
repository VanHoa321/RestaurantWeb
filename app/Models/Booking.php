<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        "customer_id",
        "branch_id",
        "table_id",
        "booking_date",
        "time_slot",
        "guest_count",
        "pre_payment",
        "status",
        "note"
    ];
    protected $primaryKey = 'id';
    protected $table = 'bookings';
    public $timestamps = false;

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
}

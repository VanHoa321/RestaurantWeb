<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceCancel extends Model
{
    use HasFactory;
    protected $fillable = [
        "invoice_id",
        "cancel_reason",
        "cancel_date",
        "cancel_by"
    ];
    protected $primaryKey = 'id';
    protected $table = 'invoice_cancels';
    public $timestamps = false;
}

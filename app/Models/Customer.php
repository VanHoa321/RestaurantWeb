<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Customer extends Authenticatable
{
    use HasFactory;

    protected $guard = 'customer';
    protected $fillable = [
        'google_id',
        'full_name',
        'avatar',
        'phone',
        'email',
        'address',
        'last_login',
        'is_active'
    ];

    protected $primaryKey = 'id';
    protected $table = 'customers';
}

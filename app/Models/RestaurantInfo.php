<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "hotline_1",
        "hot_line_2",
        "email",
        "opening_dat",
        "open_time",
        "close_time",
        "sort_description",
        "log_description"
    ];

    protected $primaryKey = 'id';
    protected $table = 'restaurant_info';
    public $timestamps = false;
}

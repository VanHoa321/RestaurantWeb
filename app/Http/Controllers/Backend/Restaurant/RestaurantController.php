<?php

namespace App\Http\Controllers\backend\restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        return view("backend.restaurant.index");
    }
}

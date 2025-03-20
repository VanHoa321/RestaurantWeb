<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\RestaurantInfo;
use Illuminate\Http\Request;

class ResInfoController extends Controller
{
    public function index()
    {
        $info = RestaurantInfo::first();
        return view("frontend.res-info.index", compact("info"));
    }
}

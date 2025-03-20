<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Combo;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Slide;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slide::where("is_active", 1)->orderBy("id","desc")->get();
        $menus = Menu::where("is_active",1)->orderBy("id", "desc")->get();
        $menu_items = MenuItem::orderBy("created_at","asc")->get();
        $combos = Combo::where("is_active",1)->orderBy("id", "desc")->take(4)->get();
        $blogs = Blog::where("is_active",1)->orderBy("id", "desc")->take(9)->get();
        return view("frontend.home.index", compact("slides", "menus", "menu_items", "combos", "blogs"));
    }
}

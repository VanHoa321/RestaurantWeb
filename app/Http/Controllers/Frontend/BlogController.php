<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Combo;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::where("is_active", 1)->withCount('blogs')->orderBy("id", "desc")->get();
        $commbos_new = Combo::where("is_active", 1)->orderBy("id", "desc")->take(4)->get();
        return view("frontend.blog.index", compact("categories", "commbos_new"));
    }

    public function getData(Request $request)
    {
        $categoryIds = $request->categoryIds;
        $search_name = $request->search_name;
        $perPage = 2;

        $blogQuery = Blog::with("user")->where("is_active", 1);

        if (!empty($categoryIds)) {
            $blogQuery->whereIn("blog_category_id", $categoryIds);
        }

        if (!empty($search_name)) {
            $blogQuery->where("title", "LIKE", "%" . $search_name . "%");
        }

        $blogs = $blogQuery->orderBy("created_at", "desc")->paginate($perPage);

        return response()->json([
            "blogs" => $blogs,
        ]);
    }

    public function detail($id){
        $blog = Blog::find($id);
        return view("frontend.blog.detail", compact("blog"));
    }
}

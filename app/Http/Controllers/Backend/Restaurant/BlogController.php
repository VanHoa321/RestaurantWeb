<?php

namespace App\Http\Controllers\backend\restaurant;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index()
    {
        $items = Blog::with("category", "user")->orderBy("id", "desc")->get();
        return view("backend.restaurant.blog.index", compact("items"));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'title' => 'required|string|max:255|min:5|unique:blogs,title,' . $id . ',id',
            'content' => 'required|string|min:20'
        ];
    
        $messages = [
            'title.required' => 'Tên bài viết không được để trống',
            'title.unique' => 'Tên bài viết đã tồn tại',
            'title.min' => 'Tên bài viết phải lớn hơn hoặc bằng :min ký tự',
            'title.max' => 'Tên bài viết không quá :max ký tự',

            'content.required'=> 'Nội dung bài viết không được để trống',
            'content.min'=> 'Nội dung bài viết phải lớn hơn hoặc bằng :min ký tự'
        ];
    
        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        $categories = BlogCategory::where("is_active", 1)->get();
        return view("backend.restaurant.blog.create", compact("categories"));
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'title' => $request->title,
            'image'=> $request->image,
            'abstract'=> $request->abstract,
            'content'=> $request->content,
            'blog_category_id'=> $request->blog_category_id,
            'user_id'=> Auth::user()->id
        ];
        $create = new Blog();
        $create->create($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới bài viết thành công"]);
        return redirect()->route("blog.index");
    }

    public function edit($id)
    {
        $edit = Blog::where("id", $id)->first();
        $categories = BlogCategory::where("is_active", 1)->get();
        return view("backend.restaurant.blog.edit", compact("edit", "categories"));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'title' => $request->title,
            'image'=> $request->image,
            'abstract'=> $request->abstract,
            'content'=> $request->content,
            'blog_category_id'=> $request->blog_category_id,
            'user_id'=> Auth::user()->id
        ];
        $edit = Blog::where('id', $id)->first();
        $edit->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật bài viết thành công"]);
        return redirect()->route("blog.index");
    }

    public function destroy(string $id)
    {
        $destroy = Blog::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa bài viết thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Bài viết không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = Blog::find($id);    
        if ($change) {
            $change->is_active = !$change->is_active;
            $change->save();
            return response()->json(['success' => true, 'message' => 'Thay đổi trạng thái thành công']);
        }
        else {
            return response()->json(['success' => false, 'message' => 'Thay đổi trạng thái không thành công'], 404);
        }
    }
}

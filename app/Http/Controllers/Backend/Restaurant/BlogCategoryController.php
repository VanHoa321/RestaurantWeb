<?php

namespace App\Http\Controllers\backend\restaurant;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $items = BlogCategory::orderBy("id", "desc")->get();
        return view("backend.restaurant.blog-category.index", compact("items"));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255|min:2|unique:blog_categories,name,' . $id . ',id'
        ];
    
        $messages = [
            'name.required' => 'Tên phân loại bài viết không được để trống',
            'name.unique' => 'Tên phân loại bài viết đã tồn tại',
            'name.min' => 'Tên phân loại bài viết phải lớn hơn hoặc bằng :min ký tự',
            'name.max' => 'Tên phân loại bài viết không quá :max ký tự',
        ];
    
        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        return view("backend.restaurant.blog-category.create");
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'alias'=> $request->alias,
            'description'=> $request->description
        ];
        $create = new BlogCategory();
        $create->create($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới phân loại bài viết thành công"]);
        return redirect()->route("blog-category.index");
    }

    public function edit($id)
    {
        $edit = BlogCategory::where("id", $id)->first();
        return view("backend.restaurant.blog-category.edit", compact("edit"));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'alias'=> $request->alias,
            'description'=> $request->description
        ];
        $edit = BlogCategory::where('id', $id)->first();
        $edit->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật phân loại bài viết thành công"]);
        return redirect()->route("blog-category.index");
    }

    public function destroy(string $id)
    {
        $destroy = BlogCategory::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa phân loại bài viết thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Phân loại bài viết không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = BlogCategory::find($id);    
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

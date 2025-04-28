<?php

namespace App\Http\Controllers\backend\item;

use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemCategoryController extends Controller
{
    public function index()
    {
        $itemCategoties = ItemCategory::orderBy("id", "desc")->get();
        return view("backend.item.item-category.index", compact("itemCategoties"));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:50|unique:item_categories,name,' . $id . ',id',
        ];

        $messages = [
            'name.required' => 'Tên danh mục mặt hàng không được để trống',
            'name.unique' => 'Tên danh mục mặt hàng đã tồn tại',
            'name.max' => 'Tên danh mục mặt hàng không quá 50 ký tự.',   
        ];

        return Validator::make($request->all(), $rules, $messages);
    }


    public function create()
    {
        return view("backend.item.item-category.create");
    }

    public function store(Request $request){
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'description'=> $request->description,
            'is_active'=> $request->is_active ? 1:0
        ];
        $create = new ItemCategory();
        $create->create($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới danh mục mặt hàng thành công"]);
        return redirect()->route("item-category.index");
    }

    public function edit($id)
    {
        $edit = ItemCategory::where("id", $id)->first();
        return view("backend.item.item-category.edit", compact("edit"));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'description'=> $request->description,
            'is_active'=> $request->is_active ? 1:0
        ];
        $edit = ItemCategory::where('id', $id)->first();
        $edit->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật danh mục mặt hàng thành công"]);
        return redirect()->route("item-category.index");
    }

    public function destroy(string $id)
    {
        $destroy = ItemCategory::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa danh mục mặt hàng thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Danh mục mặt hàng không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = ItemCategory::find($id);    
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

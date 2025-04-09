<?php

namespace App\Http\Controllers\Backend\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminMenu;
use Illuminate\Support\Facades\Validator;

class AdminMenuController extends Controller
{

    public function index()
    {
        $menus = AdminMenu::with('parents')->orderBy("id", "desc")->get();
        return view("backend.system.admin-menu.index", compact("menus"));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:50|unique:menus,name' . ($id ? ",$id" : ''),
            'level' => 'required|integer|min:1',
            'parent' => 'integer',
            'order' => 'required|integer|min:1',
            'icon' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ];

        $messages = [
            'name.required' => 'Tên Menu không được để trống!',
            'name.unique' => 'Tên Menu đã tồn tại!',
            'name.max' => 'Tên không được vượt quá 50 ký tự!',
            'level.required' => 'Cấp bậc là bắt buộc!',
            'level.integer' => 'Cấp bậc phải là số nguyên!',
            'level.min' => 'Cấp bậc phải lớn hơn 0!',
            'parent.integer' => 'Mục cha phải là số nguyên!',
            'order.required' => 'Thứ tự là bắt buộc!',
            'order.integer' => 'Thứ tự phải là số nguyên!',
            'order.min' => 'Thứ tự phải lớn hơn 0!',
            'icon.max' => 'Biểu tượng không được vượt quá 255 ký tự!',
            'route.max' => 'Đường dẫn không được vượt quá 255 ký tự!',
            'is_active.boolean' => 'Trạng thái phải là true hoặc false!',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }
    
    public function create()
    {
        $items = AdminMenu::where('is_active', true)
                ->where('parent', 0)
                ->orderBy('id', 'desc')->get();
        return view("backend.system.admin-menu.create", compact("items"));
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'level'=> $request->level,
            'parent'=> $request->parent,
            'order'=> $request->order,
            'icon'=> $request->icon,
            'route'=> $request->route,
            'is_active'=> $request->is_active ? 1:0
        ];
        $create = new AdminMenu();
        $create->create($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới Menu thành công"]);
        return redirect()->route("admin-menu.index");
    }

    public function edit($id)
    {
        $edit = AdminMenu::where("id", $id)->first();
        $lists = AdminMenu::where('is_active', true)->where('parent', 0)
        ->orderBy('id', 'desc')->get();
        return view("backend.system.admin-menu.edit", compact("edit", "lists"));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'level'=> $request->level,
            'parent'=> $request->parent,
            'order'=> $request->order,
            'icon'=> $request->icon,
            'route'=> $request->route,
            'is_active'=> $request->is_active ? 1:0
        ];
        $edit = AdminMenu::where('id', $id)->first();
        $edit->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật Menu thành công"]);
        return redirect()->route("admin-menu.index");
    }

    public function destroy(string $id)
    {
        $destroy = AdminMenu::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa Menu thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Menu không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = AdminMenu::find($id);    
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

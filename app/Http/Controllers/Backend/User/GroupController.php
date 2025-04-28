<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Models\AdminMenu;
use App\Models\Group;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::all();
        return view("backend.user.group.index", compact("groups"));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:50|unique:groups,name,' . $id . ',id',
        ];

        $messages = [
            'name.required' => 'Tên nhóm quyền không được để trống',
            'name.unique' => 'Tên nhóm quyền đã tồn tại',
            'name.max' => 'Tên nhóm quyền không quá 50 ký tự.',   
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function edit($id)
    {
        $edit = Group::where("id", $id)->first();
        return view("backend.user.group.edit", compact("edit"));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'description'=> $request->description
        ];
        $edit = Group::where('id', $id)->first();
        $edit->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật nhóm quyền thành công"]);
        return redirect()->route("group.index");
    }

    public function role(Request $request, $id){
        $group = Group::where("id", $id)->first();
        $roles = Role::where("group_id", $id)->pluck('admin_menu_id')->toArray();
        $menuParents = AdminMenu::where('parent',0)->where('is_active', true)->orderBy('order', 'asc')->get();
        $menus = AdminMenu::where('is_active', true)->orderBy('id','desc')->get();
        return view("backend.user.group.role", compact("group", "roles", "menuParents", "menus"));
    }

    public function updateRoles(Request $request, $id) {
        $newRoles = $request->input('roles', []);
    
        $group = Group::find($id);
        if (!$group) {
            return response()->json(['success' => false, 'message' => 'Nhóm quyền không tồn tại']);
        }
    
        $currentRoles = Role::where('group_id', $id)->pluck('admin_menu_id')->toArray();
    
        $rolesToDelete = array_diff($currentRoles, $newRoles);
        Role::where('group_id', $id)->whereIn('admin_menu_id', $rolesToDelete)->delete();

        $rolesToAdd = array_diff($newRoles, $currentRoles);
        foreach ($rolesToAdd as $menuId) {
            Role::create([
                'group_id' => $id,
                'admin_menu_id' => $menuId,
            ]);
        }
    
        return response()->json(['success' => true, 'message' => 'Cập nhật nhóm quyền thành công']);
    }    
}

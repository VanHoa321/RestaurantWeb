<?php

namespace App\Http\Controllers\backend\catalog;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index() 
    {
        $items = Menu::orderBy("created_at","desc")->get();
        return view("backend.catalog.menu.index", compact("items"));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|min:5|max:50|unique:menus,name,' . $id . ',id',
            'order_menu' => 'required|integer|min:1',
        ];

        $messages = [
            'name.required' => 'Tên thực đơn không được để trống!',
            'name.min' => 'Tên thực đơn phải có ít nhất :min ký tự!',
            'name.max' => 'Tên thực đơn tối đa :max ký tự!',
            'name.unique' => 'Tên thực đơn đã tồn tại!',
            
            'order_menu.required' => 'Vị trí hiển thị không được để trống!',
            'order_menu.min' => 'Vị trí hiển thị phải lớn hơn hoặc bằng :min ký tự!',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        $items = Item::where("is_active", true)->orderBy("created_at","desc")->get();
        return view("backend.catalog.menu.create", compact("items"));
    }

    public function store(Request $request){
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $menuData = [
            "name" => $request->name,
            "order_menu" => $request->order_menu,
            "avatar" => $request->avatar ? $request->avatar : "/storage/files/1/Item/no-image.jpg",
        ];
        $menu = Menu::create($menuData);
        foreach ($request->item_ids as $item_id) {
            MenuItem::create([
                'menu_id' => $menu->id,
                'item_id' => $item_id
            ]);
        }
        return response()->json(['success' => true, 'message' => 'Thêm mới thực đơn thành công']);
    }

    public function edit(string $id)
    {
        $edit = Menu::find($id);
        $items = Item::where("is_active", true)->orderBy("created_at","desc")->get();
        $menu_items = MenuItem::with("item", "item.activePrice", "item.category")->where("menu_id", $id)->get();
        return view("backend.catalog.menu.edit", compact("edit", "items", "menu_items"));
    }

    public function update(Request $request, $id){
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $menuData = [
            "name" => $request->name,
            "order_menu" => $request->order_menu,
            "avatar" => $request->avatar ? $request->avatar : "/storage/files/1/Item/no-image.jpg",
        ];
        $menu = Menu::findOrFail($id);
        $menu->update($menuData);

        $existingItems = MenuItem::where('menu_id', $id)->pluck('item_id')->toArray();

        $newItems = $request->item_ids;

        $itemsToDelete = array_diff($existingItems, $newItems);
        MenuItem::where('menu_id', $id)->whereIn('item_id', $itemsToDelete)->delete();

        $itemsToAdd = array_diff($newItems, $existingItems);
        foreach ($itemsToAdd as $item_id) {
            MenuItem::create([
                'menu_id' => $id,
                'item_id' => $item_id
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Cập nhật thực đơn thành công']);
    }

    public function destroy($id)
    {
        $destroy = Menu::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa thực đơn thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Thực đơn không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = Menu::find($id);    
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

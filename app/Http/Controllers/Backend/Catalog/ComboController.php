<?php

namespace App\Http\Controllers\backend\catalog;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use App\Models\ComboItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ComboController extends Controller
{
    public function index() 
    {
        $items = Combo::orderBy("created_at","desc")->get();
        return view("backend.catalog.combo.index", compact("items"));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|min:5|max:50|unique:menus,name,' . $id . ',id',
            'price' => 'required|integer|min:1',
        ];

        $messages = [
            'name.required' => 'Tên Combo không được để trống!',
            'name.min' => 'Tên Combo phải có ít nhất :min ký tự!',
            'name.max' => 'Tên Combo tối đa :max ký tự!',
            'name.unique' => 'Tên Combo đã tồn tại!',
            
            'price.required' => 'Giá bán không được để trống!',
            'price.min' => 'Giá bán phải lớn hơn hoặc bằng :min ký tự!',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        $items = Item::where("is_active", true)->orderBy("created_at","desc")->get();
        return view("backend.catalog.combo.create", compact("items"));
    }

    public function store(Request $request){
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $comboData = [
            "name" => $request->name,
            "price" => $request->price,
            "cost_price" => $request->cost_price,
            "avatar" => $request->avatar ? $request->avatar : "/storage/files/1/Item/no-image.jpg",
            "description" => $request->description
        ];
        $combo = Combo::create($comboData);
        foreach ($request->items as $item) {
            ComboItem::create([
                'combo_id' => $combo->id,
                'item_id' => $item['id'],
                'quantity' => $item['quantity']
            ]);
        }
        return response()->json(['success' => true, 'message' => 'Thêm mới Combo thành công']);
    }

    public function edit(string $id)
    {
        $edit = Combo::find($id);
        $items = Item::where("is_active", true)->orderBy("created_at","desc")->get();
        $combo_items = ComboItem::with("item", "item.activePrice", "item.category")->where("combo_id", $id)->get();
        return view("backend.catalog.combo.edit", compact("edit", "items", "combo_items"));
    }

    public function update(Request $request, $id){
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $comboData = [
            "name" => $request->name,
            "price" => $request->price,
            "cost_price" => $request->cost_price,
            "avatar" => $request->avatar ? $request->avatar : "/storage/files/1/Item/no-image.jpg",
            "description" => $request->description
        ];
        $combo = Combo::findOrFail($id);
        $combo->update($comboData);

        $existingItems = collect(ComboItem::where('combo_id', $id)->get()->keyBy('item_id'));

        $newItems = collect($request->items)->keyBy('id');

        $itemsToDelete = $existingItems->keys()->diff($newItems->keys());
        ComboItem::where('combo_id', $id)->whereIn('item_id', $itemsToDelete)->delete();

        foreach ($newItems as $itemId => $itemData) {
            if ($existingItems->has($itemId)) {
                ComboItem::where('combo_id', $id)->where('item_id', $itemId)
                    ->update(['quantity' => $itemData['quantity']]);
            } else {
                ComboItem::create([
                    'combo_id' => $id,
                    'item_id' => $itemId,
                    'quantity' => $itemData['quantity']
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Cập nhật Combo thành công']);
    }


    public function destroy($id)
    {
        $destroy = Combo::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa Combo thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Combo không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = Combo::find($id);    
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

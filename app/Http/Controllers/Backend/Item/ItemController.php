<?php

namespace App\Http\Controllers\backend\item;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemPrice;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::orderBy("created_at","desc")->get();
        return view("backend.item.item.index", compact("items"));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|min:5|max:50|unique:items,name,' . $id . ',id',
            'unit_id' => 'required|integer|min:1',
            'category_id' => 'required|integer|min:1',
        ];

        $messages = [
            'name.required' => 'Tên mặt hàng không được để trống!',
            'name.min' => 'Tên mặt hàng phải có ít nhất :min ký tự!',
            'name.max' => 'Tên mặt hàng tối đa :max ký tự!',
            'name.unique' => 'Tên mặt hàng đã tồn tại!',
            
            'unit_id.required' => 'Vui lòng chọn đơn vị tính!',
            'unit_id.min' => 'Vui lòng chọn đơn vị tính!',

            'category_id.required' => 'Vui lòng chọn danh mục sản phẩm!',
            'category_id.min' => 'Vui lòng chọn danh mục sản phẩm!',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }


    public function create()
    {
        $units = Unit::where('is_active', true)->orderBy('id', 'desc')->get();
        $categories = ItemCategory::where('is_active', true)->orderBy('id', 'desc')->get();
        return view("backend.item.item.create", compact("units", "categories"));
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $itemData = [
            "name" => $request->name,
            "unit_id" => $request->unit_id,
            "category_id" => $request->category_id,
            "avatar" => $request->avatar,
            "description" => $request->description
        ];

        $item = Item::create($itemData);
        $item_id = $item->id;

        $price = [
            "item_id" => $item_id,
            "name"=> $request->price_name,
            "sale_price" => $request->sale_price,
            "code_price" => $request->cod_price,
        ];

        $itemPrice = ItemPrice::create($price);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới mặt hàng thành công"]);
        return redirect()->route("item.index");
    }

    public function edit(string $id)
    {
        $edit = Item::where("id", $id)->first();
        $units = Unit::where('is_active', true)->orderBy('id', 'desc')->get();
        $categories = ItemCategory::where('is_active', true)->orderBy('id', 'desc')->get();
        $prices = ItemPrice::where('item_id', $id)->orderBy('id','desc')->get();
        return view("backend.item.item.edit", compact("edit", "units", "categories", "prices"));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $itemData = [
            "name" => $request->name,
            "unit_id" => $request->unit_id,
            "category_id" => $request->category_id,
            "avatar" => $request->avatar,
            "description" => $request->description
        ];

        $edit = Item::where("id", $id)->update($itemData);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật mặt hàng thành công"]);
        return redirect()->route("item.index");
    }

    public function destroy($id)
    {
        $destroy = Item::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa mặt hàng thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Mặt hàng không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = Item::find($id);    
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

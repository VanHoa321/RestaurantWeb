<?php

namespace App\Http\Controllers\backend\item;

use App\Http\Controllers\Controller;
use App\Models\ItemPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemPriceController extends Controller
{

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:50',
            'price_cod' => 'required|numeric|min:1',
            'price_sale' => 'required|numeric|min:1',
            'description' => 'nullable|string'
        ];
        
        $messages = [
            'name.required' => 'Tên giá không được để trống!',
            'name.string' => 'Tên giá phải là một chuỗi ký tự!',
            'name.max' => 'Tên giá tối đa :max ký tự!',
            
            'price_cod.required' => 'Giá vốn không được để trống!',
            'price_cod.numeric' => 'Giá vốn phải là số!',
            'price_cod.min' => 'Giá vốn phải lớn hơn hoặc bằng :min!',
            
            'price_sale.required' => 'Giá bán không được để trống!',
            'price_sale.numeric' => 'Giá bán phải là số!',
            'price_sale.min' => 'Giá bán phải lớn hơn hoặc bằng :min!',
            
            'description.string' => 'Mô tả phải là một chuỗi ký tự!'
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = [
            'item_id' => $request->item_id,
            'name' => $request->name,
            'sale_price' => $request->price_sale,
            'code_price' => $request->price_cod,
            'description' => $request->description,
            'is_active' => 0
        ];

        $item = ItemPrice::create($data);
        if ($item) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm giá bán thành công'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi khi thêm mới giá'
        ]);
    }

    public function edit($id)
    {
        $price = ItemPrice::find($id);
        return response()->json($price);
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = [
            'item_id' => $request->item_id,
            'name' => $request->name,
            'sale_price' => $request->price_sale,
            'code_price' => $request->price_cod,
            'description' => $request->description,
            'is_active' => 0
        ];

        $item = ItemPrice::where("id", $id)->update($data);
        if ($item) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật giá bán thành công'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi khi cập nhật giá'
        ]);
    }

    public function destroy($id)
    {
        $destroy = ItemPrice::find($id);
        if ($destroy) {
            if ($destroy->is_active == 1) {
                return response()->json(['danger' => false, 'message' => 'Giá bán đang được sử dụng']);
            }
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa giá mặt hàng thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Giá mặt hàng không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = ItemPrice::find($id);    
        if ($change) {
            ItemPrice::where('item_id', $change->item_id)->update(['is_active' => 0]);
            $change->is_active = 1;
            $change->save();
            return response()->json(['success' => true, 'message' => 'Thay đổi trạng thái thành công']);
        }
        else {
            return response()->json(['success' => false, 'message' => 'Thay đổi trạng thái không thành công'], 404);
        }
    }
}

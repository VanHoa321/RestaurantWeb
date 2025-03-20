<?php

namespace App\Http\Controllers\backend\restaurant;

use App\Http\Controllers\Controller;
use App\Models\TableType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TableTypeController extends Controller
{

    public function index()
    {
        $items = TableType::orderBy("id", "desc")->get();
        return view("backend.restaurant.table-type.index", compact("items"));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:100|min:5|unique:table_types,name,' . $id . ',id',
            'max_seats' => 'required|min:1|max:30',
        ];
    
        $messages = [
            'name.required' => 'Tên loại bàn không được để trống',
            'name.unique' => 'Tên loại bàn đã tồn tại',
            'name.min' => 'Tên loại bàn phải lớn hơn hoặc bằng :min ký tự.',
            'name.max' => 'Tên loại bàn không quá :max ký tự.',
            
            'max_seats.required' => 'Số chỗ ngồi không được để trống',
            'max_seats.min' => 'Số chỗ ngồi phải lớn hơn hoặc bằng :min',
            'max_seats.max' => 'Số chỗ ngồi không được quá :max',
        ];
    
        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        return view("backend.restaurant.table-type.create");
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'max_seats'=> $request->max_seats,
            'description'=> $request->description,
        ];
        $create = new TableType();
        $create->create($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới loại bàn thành công"]);
        return redirect()->route("table-type.index");
    }

    public function edit($id)
    {
        $edit = TableType::where("id", $id)->first();
        return view("backend.restaurant.table-type.edit", compact("edit"));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'max_seats'=> $request->max_seats,
            'description'=> $request->description,
        ];
        $edit = TableType::where('id', $id)->first();
        $edit->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật loại bàn thành công"]);
        return redirect()->route("table-type.index");
    }

    public function destroy(string $id)
    {
        $destroy = TableType::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa loại bàn thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Loại bàn không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = TableType::find($id);    
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

<?php

namespace App\Http\Controllers\backend\restaurant;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Table;
use App\Models\TableType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TableController extends Controller
{
    public function index()
    {
        $items = Table::with("area", "type")->orderBy("id","desc")->get();
        return view("backend.restaurant.table.index", compact("items"));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:100|min:5|unique:tables,name,' . $id . ',id',
        ];
    
        $messages = [
            'name.required' => 'Tên bàn ăn không được để trống',
            'name.unique' => 'Tên bàn ăn đã tồn tại',
            'name.min' => 'Tên bàn ăn phải lớn hơn hoặc bằng :min ký tự.',
            'name.max' => 'Tên bàn ăn không quá :max ký tự.',
        ];
    
        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        $types = TableType::where("is_active", 1)->orderBy("id","desc")->get();
        $areas = Area::where("is_active", 1)->orderBy("id","desc")->get();
        return view("backend.restaurant.table.create", compact("types","areas"));
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'area_id'=> $request->area_id,
            'type_id'=> $request->type_id,
            'description'=> $request->description
        ];
        $create = new Table();
        $create->create($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới bàn ăn thành công"]);
        return redirect()->route("table.index");
    }

    public function edit($id)
    {
        $edit = Table::find($id);
        $types = TableType::where("is_active", 1)->orderBy("id","desc")->get();
        $areas = Area::where("is_active", 1)->orderBy("id","desc")->get();
        return view("backend.restaurant.table.edit", compact("edit", "types", "areas"));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'area_id'=> $request->area_id,
            'type_id'=> $request->type_id,
            'description'=> $request->description
        ];
        $edit = Table::where('id', $id)->first();
        $edit->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật bàn ăn thành công"]);
        return redirect()->route("table.index");
    }

    public function destroy(string $id)
    {
        $destroy = Table::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa bàn thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Bàn không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = Table::find($id);    
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

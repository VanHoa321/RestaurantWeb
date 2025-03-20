<?php

namespace App\Http\Controllers\backend\item;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::orderBy("id", "desc")->get();
        return view("backend.item.unit.index", compact("units"));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:50|unique:units,name,' . $id . ',id',
        ];

        $messages = [
            'name.required' => 'Tên đơn vị tính không được để trống',
            'name.unique' => 'Tên đơn vị tính đã tồn tại',
            'name.max' => 'Tên đơn vị tính không quá 50 ký tự.',   
        ];

        return Validator::make($request->all(), $rules, $messages);
    }


    public function create()
    {
        return view("backend.item.unit.create");
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
        $create = new Unit();
        $create->create($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới đơn vị tính thành công"]);
        return redirect()->route("unit.index");
    }

    public function edit($id)
    {
        $edit = Unit::where("id", $id)->first();
        return view("backend.item.unit.edit", compact("edit"));
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
        $edit = Unit::where('id', $id)->first();
        $edit->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật đơn vị tính thành công"]);
        return redirect()->route("unit.index");
    }

    public function destroy(string $id)
    {
        $destroy = Unit::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa đơn vị tính thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Đơn vị tính không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = Unit::find($id);    
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

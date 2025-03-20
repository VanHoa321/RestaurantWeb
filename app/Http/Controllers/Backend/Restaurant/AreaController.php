<?php

namespace App\Http\Controllers\backend\restaurant;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    public function index()
    {
        $items = Area::with("branch")->orderBy("id","desc")->get();
        return view("backend.restaurant.area.index", compact("items"));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:50|min:5|unique:areas,name,' . $id . ',id',
        ];
    
        $messages = [
            'name.required' => 'Tên khu vực không được để trống',
            'name.unique' => 'Tên khu vực đã tồn tại',
            'name.min' => 'Tên khu vực phải lớn hơn hoặc bằng :min',
            'name.max' => 'Tên khu vực không quá :max',
        ];
    
        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        $branchs = Branch::where("is_active", 1)->orderBy("id","asc")->get();
        return view("backend.restaurant.area.create", compact("branchs"));
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'branch_id'=> $request->branch_id,
            'description'=> $request->description,
        ];
        $create = new Area();
        $create->create($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới khu vực thành công"]);
        return redirect()->route("area.index");
    }

    public function edit($id)
    {
        $edit = Area::where("id", $id)->first();
        $branchs = Branch::where("is_active", 1)->orderBy("id","asc")->get();
        return view("backend.restaurant.area.edit", compact("edit", "branchs"));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'branch_id'=> $request->branch_id,
            'description'=> $request->description,
        ];
        $edit = Area::where('id', $id)->first();
        $edit->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật khu vực thành công"]);
        return redirect()->route("area.index");
    }

    public function destroy(string $id)
    {
        $destroy = Area::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa khu vực thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Khu vực không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = Area::find($id);    
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

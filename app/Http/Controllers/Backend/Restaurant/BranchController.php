<?php

namespace App\Http\Controllers\backend\restaurant;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    public function index()
    {
        $items = Branch::all();
        return view("backend.restaurant.branch.index", compact("items"));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:100|min:5|unique:branchs,name,' . $id . ',id',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^(0[1-9][0-9]{8,9})$/',
            'email' => 'required|email|max:100',
        ];
    
        $messages = [
            'name.required' => 'Tên cơ sở không được để trống',
            'name.unique' => 'Tên cơ sở đã tồn tại',
            'name.min' => 'Tên cơ sở phải lớn hơn hoặc bằng :min ký tự.',
            'name.max' => 'Tên cơ sở không quá :max ký tự.',
            
            'address.required' => 'Địa chỉ không được để trống',
            'address.max' => 'Địa chỉ không được quá :max ký tự',
    
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.regex' => 'Số điện thoại không hợp lệ',
    
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không quá :max ký tự',
        ];
    
        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        return view("backend.restaurant.branch.create");
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'address'=> $request->address,
            'phone'=> $request->phone,
            'email'=> $request->email,
            'description'=> $request->description,
        ];
        $create = new Branch();
        $create->create($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới cơ sở thành công"]);
        return redirect()->route("branch.index");
    }

    public function edit(string $id)
    {
        $edit = Branch::where("id", $id)->first();
        return view("backend.restaurant.branch.edit", compact("edit"));
    }

    public function update(Request $request, string $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'name' => $request->name,
            'address'=> $request->address,
            'phone'=> $request->phone,
            'email'=> $request->email,
            'description'=> $request->description,
        ];
        $edit = Branch::where('id', $id)->first();
        $edit->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Chỉnh sửa cơ sở thành công"]);
        return redirect()->route("branch.index");
    }

    public function destroy(string $id)
    {
        $destroy = Branch::find($id);
        if ($destroy) {
            $destroy->delete();
            return response()->json(['success' => true, 'message' => 'Xóa cơ sở nhà hàng thành công']);
        } else {
            return response()->json(['danger' => false, 'message' => 'Cơ sở nhà hàng không tồn tại'], 404);
        }
    }

    public function changeActive($id){
        $change = Branch::find($id);    
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

<?php

namespace App\Http\Controllers\backend\customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $items = Customer::where("id", ">", 1)->orderBy("id", "desc")->get();
        return view("backend.customer.index", compact("items"));
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'full_name' => 'required|string|max:100|min:5',
            'phone' => 'required|string|regex:/^(0[1-9][0-9]{8,9})$/|unique:customers,phone,' . $id . ',id'
        ];
    
        $messages = [
            'full_name.required' => 'Tên khách hàng không được để trống',
            'full_name.min' => 'Tên khách hàng phải lớn hơn hoặc bằng :min ký tự.',
            'full_name.max' => 'Tên khách hàng không quá :max ký tự.',
    
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'phone.unique' => 'Số điện thoại đã tồn tại',
        ];
    
        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        return view("backend.customer.create");
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'full_name' => $request->full_name,
            'avatar' => $request->avatar ? $request->avatar : "/storage/files/1/Avatar/12225935.png",
            'phone'=> $request->phone,
            'address'=> $request->address
        ];
        $create = new Customer();
        $create->create($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới khách hàng thành công"]);
        return redirect()->route("customer.index");
    }

    public function edit($id)
    {
        $edit = Customer::find($id);
        return view("backend.customer.edit", compact("edit"));
    }

    public function update(Request $request, $id)
    {
        $validator = $this->validates($request, $id);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $data = [
            'full_name' => $request->full_name,
            'avatar' => $request->avatar ? $request->avatar : "/storage/files/1/Avatar/12225935.png",
            'phone'=> $request->phone,
            'address'=> $request->address
        ];
        $edit = Customer::find($id);
        $edit->update($data);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Cập nhật thông tin khách hàng thành công"]);
        return redirect()->route("customer.index");
    }

    public function destroy($id)
    {
        $destroy = Customer::find($id);

        if (!$destroy) {
            return response()->json(['success' => false, 'message' => 'Khách hàng không tồn tại'], status: 404);
        }
        $check = Invoice::where("customer_id", $id)->exists();

        if ($check) {
            return response()->json(['success' => false, 'message' => 'Khách hàng từng dùng bữa không thể xóa'], 200);
        }

        $destroy->delete();
        return response()->json(['success' => true, 'message' => 'Xóa khách hàng thành công']);
    }

    public function changeActive($id){
        $change = Customer::find($id);    
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

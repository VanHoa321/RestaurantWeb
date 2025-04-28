<?php

namespace App\Http\Controllers\backend\user;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $items = User::with("branch", "group")->orderBy("id", "desc")->get();
        return view("backend.user.user.index", compact("items")); 
    }

    private function validates(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|min:3|max:50',
            'user_name' => 'required|string|min:3|max:30|unique:users,user_name,' . $id . ',id',
            'email' => 'required|email|max:100|unique:users,email,' . $id . ',id',
            'phone' => 'required|string|regex:/^0[0-9]{9}$/|unique:users,phone,' . $id . ',id',
            'password' => $id ? 'nullable|min:6' : 'required|min:6',
        ];

        $messages = [
            'name.required' => 'Họ tên không được để trống!',
            'name.min' => 'Họ tên phải có ít nhất :min ký tự!',
            'name.max' => 'Họ tên tối đa :max ký tự!',

            'user_name.required' => 'Tên đăng nhập không được để trống!',
            'user_name.min' => 'Tên đăng nhập phải có ít nhất :min ký tự!',
            'user_name.max' => 'Tên đăng nhập tối đa :max ký tự!',
            'user_name.unique' => 'Tên đăng nhập đã tồn tại!',

            'email.required' => 'Email không được để trống!',
            'email.email' => 'Email không hợp lệ!',
            'email.max' => 'Email tối đa :max ký tự!',
            'email.unique' => 'Email đã tồn tại!',

            'phone.required' => 'Số điện thoại không được để trống!',
            'phone.regex' => 'Số điện thoại phải có 10 chữ số và bắt đầu bằng số 0!',
            'phone.unique' => 'Số điện thoại đã tồn tại!',

            'password.required' => 'Mật khẩu không được để trống!',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự!',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    public function create()
    {
        $branchs = Branch::where('is_active', 1)->orderBy('id', 'asc')->get();
        $groups = Group::all();
        return view("backend.user.user.create", compact("branchs", "groups"));
    }

    public function store(Request $request)
    {
        $validator = $this->validates($request);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $userData = [
            "name" => $request->name,
            "user_name" => $request->user_name,
            "email" => $request->email,
            "phone" => $request->phone,
            "avatar" => $request->avatar ? $request->avatar : "/storage/files/1/Avatar/12225935.png",
            "password" => $request->password,
            "group_id" => $request->group_id,
            "branch_id" => $request->branch_id
        ];

        $create = User::create($userData);
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Thêm mới nhân viên thành công"]);
        return redirect()->route("user.index");
    }

    public function show($id)
    {
        $user = User::with("branch", "group")->where("id", $id)->first();
        return view("backend.user.user.show", compact('user'));
    }

    public function changeActive($id){
        $change = User::find($id);    
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

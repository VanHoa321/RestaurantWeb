<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function login(){
        return view("account.login");
    }

    public function postLogin(Request $request) {
        $user = User::where('user_name', $request->user_name)->first();
        $remember = $request->has('remember');
        if ($user) {
            if ($user->is_active == 0) {
                $request->session()->put("messenge", ["style" => "danger", "msg" => "Tài khoản của bạn đã bị khóa"]);
                return redirect()->route("login");
            }
    
            if (Auth::attempt(["user_name" => $request->user_name, "password" => $request->password, "group_id" => 1], $remember)) {
                $user->update(['last_login' => now()]);
                $request->session()->put("messenge", ["style" => "success", "msg" => "Đăng nhập quyền quản lý nhà hàng thành công"]);
                return redirect()->route("dashboard.index");
            }
            elseif (Auth::attempt(["user_name" => $request->user_name, "password" => $request->password, "group_id" => 2], $remember)) {
                $user->update(['last_login' => now()]);
                $request->session()->put("messenge", ["style" => "success", "msg" => "Đăng nhập quyền nhân viên thành công"]);
                return redirect()->route("inv-present.index");
            } 
        }
        $request->session()->put("messenge", ["style" => "danger", "msg" => "Thông tin tài khoản không đúng"]);
        return redirect()->route("login");
    }

    public function logout(){
        Auth::logout();
        return redirect()->route("home.index");
    }

    public function profile(){
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }

    public function editProfile(){
        $user = Auth::user();
        return view('account.edit_profile', compact("user"));
    }

    public function updateProfile(Request $request){
        $oldUser = Auth::user();
        $id = $oldUser->id;
        $update = User::find($id);
        $data = [
            'name' => $request->name,
            'email'=> $request->email,
            'phone'=> $request->phone,
            'avatar'=> $request->avatar,
            'description'=> $request->description,
        ];
        $update->update($data);
        $request->session()->put("messenge", ["style" => "success", "msg" => "Cập nhật hồ sơ thành công"]);
        return redirect()->route('profile');
    }

    public function editPassword(){
        $user = Auth::user();
        return view('account.edit_password', compact("user"));
    }

    public function updatePassword(Request $request){
        $user = Auth::user();
        if (!Hash::check($request->old_password, $user->password)) {
            $request->session()->put("messenge", ["style" => "danger", "msg" => "Mật khẩu cũ không đúng"]);
            return redirect()->back();
        }
        $id = $user->id;
        $update = User::find($id);
        $update->password = Hash::make($request->new_password);
        $update->save();
        $request->session()->put("messenge", ["style" => "success", "msg" => "Cập nhật mật khẩu thành công"]);
        return redirect()->route('profile');
    }
}

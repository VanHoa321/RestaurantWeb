<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class AccountController extends Controller
{
    public function login(){
        return view("account.login");
    }

    public function postLogin(Request $request) {
        $user = User::where('user_name', $request->user_name)->first();
        if ($user) {
            if (is_null($user->email_verified_at)) {
                $request->session()->put("messenge", ["style" => "danger", "msg" => "Tài khoản chưa được xác minh"]);
                return redirect()->route("login");
            }
            if ($user->is_active == 0) {
                $request->session()->put("messenge", ["style" => "danger", "msg" => "Tài khoản của bạn đã bị khóa"]);
                return redirect()->route("login");
            }
    
            if (Auth::attempt(["user_name" => $request->user_name, "password" => $request->password, "group_id" => 1])) {
                $request->session()->put("messenge", ["style" => "success", "msg" => "Đăng nhập quyền quản lý nhà hàng thành công"]);
                return redirect()->route("home.index");
            }
            elseif (Auth::attempt(["user_name" => $request->user_name, "password" => $request->password, "group_id" => 2])) {
                $request->session()->put("messenge", ["style" => "success", "msg" => "Đăng nhập quyền nhân viên thành công"]);
                return redirect()->route("homeT.index");
            } 
        }
        $request->session()->put("messenge", ["style" => "danger", "msg" => "Thông tin tài khoản không đúng"]);
        return redirect()->route("login");
    }
}

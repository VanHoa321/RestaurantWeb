<?php

namespace App\Http\Controllers\account;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\PasswordReset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm(){
        return view('account.forgot_password');
    }

    public function sendResetLink(Request $request){
        $validator = Validator::make($request->all(),[
            'email_pw' => 'required|email|exists:users,email',
        ], [
            'email_pw.required' => 'Email là bắt buộc',
            'email_pw.email' => 'Email không hợp lệ',
            'email_pw.exists' => 'Email không tồn tại trong hệ thống',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $user = User::where('email', $request->email_pw)->first();
        $token = Str::random(64);
        $create = new PasswordReset();
        $create->email = $request->email_pw;
        $create->token = $token;
        $create->created_at = Carbon::now()->addMinutes(5);
        $create->save();

        Mail::to('vanhoa12092003@gmail.com')->send(new PasswordResetMail($user, $token));

        $request->session()->put("messenge", ["style"=>"success","msg"=>"Đã gửi email lấy lại mật khẩu"]);
        return redirect()->route("password.request");
    }

    public function showResetPasswordForm(Request $request) {
        $token = $request->token;
        $passwordReset = PasswordReset::where('token', $token)->first();
        if (!$passwordReset) {
            $request->session()->put("messenge", ["style" => "danger", "msg" => "Token đổi mật khẩu không hợp lệ !"]);
            return redirect()->route("password.request");
        }
    
        $currentTime = Carbon::now();
        $createdTime = Carbon::parse($passwordReset->created_at);
    
        if ($currentTime > $createdTime) {
            $request->session()->put("messenge", ["style" => "danger", "msg" => "Đã hết hạn đổi mật khẩu tài khoản !"]);
            return redirect()->route("password.request");
        }

        return view('account.reset_password', compact("token"));
    }   
    
    public function resetPassword(Request $request) {
    
        $token = $request->token;
        $passwordReset = PasswordReset::where('token', $token)->first();
    
        if (!$passwordReset) {
            $request->session()->put("messenge", ["style" => "danger", "msg" => "Token đổi mật khẩu không hợp lệ !"]);
            return redirect()->route("password.request");
        }
    
        $user = User::where('email', $passwordReset->email)->first();
    
        if (!$user) {
            $request->session()->put("messenge", ["style" => "danger", "msg" => "Người dùng không tồn tại !"]);
            return redirect()->route("password.request");
        }
    
        $user->password = Hash::make($request->pw);
        $user->save();
        PasswordReset::where('token', $token)->delete();
        $request->session()->put("messenge", ["style" => "success", "msg" => "Mật khẩu đã được đổi thành công !"]);  
        return redirect()->route('login');
    }    
}

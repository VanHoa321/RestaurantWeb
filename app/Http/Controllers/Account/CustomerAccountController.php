<?php

namespace App\Http\Controllers\account;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class CustomerAccountController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $googleUser = Socialite::driver('google')->user();
        return $this->handleUserLogin($googleUser, $request);
    }

    protected function handleUserLogin($user, Request $request)
    {
        $check = Customer::where('google_id', $user->getId())->first();

        if (!$check) {
            $check = Customer::create([
                'google_id' => $user->getId(),
                'full_name' => $user->getName(),
                'avatar' => $user->getAvatar(),
                'email' => $user->getEmail(),
                'phone' => $user->phone ? $user->phone : '',
                'address' => $user->address ? $user->address : '',
                'last_login' => now()
            ]);
        } 
        else {
            if ($check->is_active == 0) {
                $request->session()->put("messenge", ["style"=>"danger","msg"=>"Tài khoản của bạn đã bị khóa"]);
                return redirect()->route("home.index");
            }
        }
        Auth::guard('customer')->login($check);
        // $request->session()->put("messenge", ["style"=>"success","msg"=>"Đăng nhập thành công"]);
        return redirect()->route("home.index");
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        // $request->session()->put("messenge", ["style"=>"success","msg"=>"Đăng xuất thành công"]);
        return redirect()->route("home.index");
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\AdminMenu;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) {
            $request->session()->put("messenge", ["style"=>"danger","msg"=>"Vui lòng đăng nhập hệ thống!"]);
            return redirect()->route("login");
        }

        $allowedMenus = $user->group->roles->pluck('admin_menu_id')->toArray();
        $currentRoute = $request->route()->getName();
        $menu = AdminMenu::where('route', $currentRoute)->first();
        if (!$menu) {
            $baseRoute = explode('.', $currentRoute)[0];
            $menu = AdminMenu::where('route', 'LIKE', "%$baseRoute%")->first();
            if (!$menu) {
                $menu = AdminMenu::where('id', 8)->first();
            }
        }

        $parentMenuId = ($menu->parent == 0) ? $menu->id : $menu->parent;
        
        if (!in_array($parentMenuId, $allowedMenus)) {
            $request->session()->put("messenge", ["style"=>"danger","msg"=>"Không đủ quyền truy cập hệ thống"]);
            return redirect()->route("login");
        }

        return $next($request);
    }
}

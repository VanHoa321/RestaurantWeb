<?php

namespace App\View\Components;

use App\Models\User;
use App\Models\AdminMenu;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class AdminMenuComponent extends Component
{

    public $parentMenus;
    public $subMenus;
    public function __construct()
    {  
        $user = new User();
        $user = $user->find(Auth::id());
        $this->parentMenus = $user->roles()
        ->whereHas('menu', function ($query) {
            $query->where('is_active', 1);
        })
        ->with(['menu' => function ($query) {
            $query->where('is_active', 1)->orderBy('id', 'asc');
        }])
        ->get()
        ->pluck('menu')
        ->unique();
        
        $parentMenuIds = $this->parentMenus->pluck('id')->toArray();

        $this->subMenus = AdminMenu::whereIn('parent', $parentMenuIds)
            ->where('is_active', 1)
            ->orderBy('order', 'asc')
            ->get();
    }

    public function render(): View|Closure|string
    {
        return view('components.admin-menu-component');
    }
}

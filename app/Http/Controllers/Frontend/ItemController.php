<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use App\Models\Item;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $menus = Menu::where("is_active", 1)->withCount('menuItems')->orderBy("id", "desc")->get();
        $comboCount = Combo::where("is_active", 1)->count();
        return view("frontend.item.index", compact("menus", "comboCount"));
    }

    public function getData(Request $request)
    {
        $menuIds = $request->menu_ids;
        $comboFilter = $request->combo;
        $search_name = $request->search_name;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $perPage = 8;
        $currentPage = request("page", 1);
        $menuItems = collect();
        $combos = collect();
        $menuItemsCount = 0;
        $combosCount = 0;

        if (!empty($menuIds)) {
            $menuItemsQuery = MenuItem::whereIn("menu_id", $menuIds)
            ->with(["item" => function ($query) {
                $query->with("activePrice");
            }]);

            if (!empty($search_name)) {
                $menuItemsQuery->whereHas('item', function ($query) use ($search_name) {
                    $query->where("name", "LIKE", "%" . $search_name . "%");
                });
            }

            $menuItemsQuery->whereHas('item.activePrice', function ($query) use ($min_price, $max_price) {
                $query->whereBetween("sale_price", [$min_price, $max_price]);
            });
        
            $menuItemsQuery->orderBy("created_at", "asc");
            $menuItemsCount = $menuItemsQuery->count();
        }

        if ($comboFilter == 1) {
            $comboQuery = Combo::where("is_active", 1);

            if (!empty($search_name)) {
                $comboQuery->where("name", "LIKE", "%" . $search_name . "%");
            }
            $comboQuery->whereBetween("price", [$min_price, $max_price]);

            $comboQuery->orderBy("created_at", "desc");
            $combosCount = $comboQuery->count();
        }

        $totalItemsCount = $menuItemsCount + $combosCount;
        $lastPage = ceil($totalItemsCount / $perPage);

        // Lấy dữ liệu đúng theo phân trang
        $offset = ($currentPage - 1) * $perPage;
        $remainingSlots = $perPage;

        if ($menuItemsCount > $offset) {
            $menuItems = $menuItemsQuery->skip($offset)->take($remainingSlots)->get();
            $remainingSlots -= $menuItems->count();
        }

        if ($comboFilter == 1 && $remainingSlots > 0 && $combosCount > 0) {
            $combos = $comboQuery->skip(max(0, $offset - $menuItemsCount))->take($remainingSlots)->get();
        }

        return response()->json([
            "menu_items" => $menuItems,
            "combos" => $combos,
            "current_page" => $currentPage,
            "last_page" => $lastPage,
            "prev_page_url" => $currentPage > 1 ? url()->current() . "?page=" . ($currentPage - 1) : null,
            "next_page_url" => $currentPage < $lastPage ? url()->current() . "?page=" . ($currentPage + 1) : null
        ]);
    }

    public function getItem($id)
    {
        $item = Item::with("category", "activePrice")->where("id", $id)->first();
        if(!$item)
        {
            return response()->json([
                "success" => false,
                "message" => "Không tìm thấy sản phẩm này"
            ]);
        }

        return response()->json([
            "success" => true,
            "item" => $item
        ]);
    }

    public function getCombo($id)
    {
        $combo = Combo::find($id);
        if(!$combo)
        {
            return response()->json([
                "success" => false,
                "message" => "Không tìm thấy combo này"
            ]);
        }

        return response()->json([
            "success" => true,
            "combo" => $combo
        ]);
    }
}

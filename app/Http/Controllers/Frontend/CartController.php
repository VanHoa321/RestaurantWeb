<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Combo;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function PHPSTORM_META\type;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);
    
        $discount = 0;
        $total = $subtotal - $discount;
        return view('frontend.cart.index', compact('subtotal', 'discount', 'total'));
    }

    public function getCart()
    {
        $cart = session('cart') ?? [];
        $subtotal = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        return response()->json([
            'cart' => $cart,
            'subtotal' => number_format($subtotal, 0, ',', '.'),
            'cartCount' => count($cart)
        ]);
    }

    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);

        $id = $request->id;
        $quantity = $request->quantity ?? 1;
        $type = $request->type;
        $cartKey = $type . '_' . $id;
        $img = '';
        $name = '';
        $price = 0;

        if ($type == 'item') {
            $item = MenuItem::with('item.activePrice')->where("item_id", $id)->first();
            if ($item && $item->item && $item->item->activePrice) {
                $name = $item->item->name;
                $price = $item->item->activePrice->sale_price;
                $img = $item->item->avatar;
            }
        } 
        elseif ($type == 'combo') {
            $item = Combo::find($id);
            if ($item) {
                $name = $item->name;
                $price = $item->price;
                $img = $item->avatar;
            }
        }

        if (!$name || $price <= 0) {
            return response()->json(['error' => 'Sản phẩm không hợp lệ hoặc không tồn tại'], 400);
        }

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } 
        else {
            $cart[$cartKey] = [
                'id' => $id,
                'img' => $img,
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'type' => $type
            ];
        }

        session()->put('cart', $cart);
        return response()->json(['success' => 'Thêm vào giỏ hàng thành công!', 'cart' => $cart, 'count' => count($cart)]);
    }

    public function updateCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $cartKey = $request->type . '_' . $request->id;
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return response()->json(['success' => 'Cập nhật giỏ hàng thành công!', 'cart' => $cart]);
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $cartKey = $request->type . '_' . $request->id;
        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            session()->put('cart', $cart);
            $subtotal = array_reduce($cart, function ($carry, $item) {
                return $carry + ($item['price'] * $item['quantity']);
            }, 0);
        
            $discount = 0;
            $total = $subtotal - $discount;
        }

        return response()->json(['success' => 'Xóa sản phẩm thành công!', 'cart' => $cart, 'count' => count($cart), 'subtotal' => $subtotal, 'discount' => $discount, 'total' => $total]);
    }

    public function clearCart(Request $request)
    {
        session()->forget('cart');
        $request->session()->put("messenge", ["style"=>"success","msg"=>"Làm mới giỏ hàng thành công"]);
        return redirect()->route("cart.index");
    }
}

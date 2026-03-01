<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request, $id)
    {
        if (!auth()->check()) {
            return back()->with('error', 'يجب عليك تسجيل الدخول أولاً لإضافة منتجات للسلة.');
        }

        $product = Product::findOrFail($id);

        $cart = Cart::firstOrCreate(
            [
                'user_id'   => auth()->id(),
                'tenant_id' => tenant('id') 
            ]
        );

        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $id)
                            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $id,
                'quantity'   => 1
            ]);
        }

        return back()->with('success', 'تمت إضافة المنتج إلى السلة بنجاح! 🛒');
    }

    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('shop.index')->with('error', 'يجب عليك تسجيل الدخول أولاً لعرض السلة.');
        }

        $tenant = tenant();

        $cart = Cart::with('items.product')
                    ->where('user_id', auth()->id())
                    ->where('tenant_id', tenant('id'))
                    ->first();

        return view('customer.cart.index', compact('cart', 'tenant'));
    }
}
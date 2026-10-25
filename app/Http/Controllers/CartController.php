<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Support\Cart;

class CartController extends Controller {
    public function index() { return view('cart', ['lines' => Cart::lines(), 'total' => Cart::total()]); }

    public function add(Product $product) {
        $cart = session('cart', []);
        $have = $cart[$product->id] ?? 0;
        if ($product->stock < 1) {
            $msg = "Sorry, “{$product->title}” is sold out.";
        } else {
            $new = min($have + max(1, (int) request('qty', 1)), $product->stock);
            $cart[$product->id] = $new;
            session(['cart' => $cart]);
            $msg = $new === $have
                ? "Only {$product->stock} in stock, and they are all in your cart."
                : "“{$product->title}” added to your cart.";
        }
        if (request()->expectsJson()) return response()->json(['message' => $msg, 'cart' => Cart::count()]);
        return back()->with('status', $msg);
    }
    public function update(Product $product) {
        $qty = (int) request('qty'); $cart = session('cart', []);
        if ($qty < 1) unset($cart[$product->id]); else $cart[$product->id] = min($qty, $product->stock);
        session(['cart' => $cart]);
        return back();
    }
    public function remove(Product $product) { session()->forget("cart.{$product->id}"); return back(); }
}

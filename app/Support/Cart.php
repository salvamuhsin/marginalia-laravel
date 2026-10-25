<?php
namespace App\Support;
use App\Models\Product;

/** Session cart: ['product_id' => quantity] */
class Cart {
    public static function lines() {
        $cart = session('cart', []);
        return Product::with('category')->whereIn('id', array_keys($cart))->get()->map(fn ($p) => (object) [
            'product' => $p, 'qty' => $cart[$p->id], 'total' => $p->price * $cart[$p->id],
        ]);
    }
    public static function total(): int { return (int) self::lines()->sum('total'); }
    public static function count(): int { return array_sum(session('cart', [])); }
    public static function money(int $cents): string { return config('shop.currency') . number_format($cents / 100, 2); }
}

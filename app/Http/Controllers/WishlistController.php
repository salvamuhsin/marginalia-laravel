<?php
namespace App\Http\Controllers;
use App\Models\Product;

class WishlistController extends Controller {
    public function index() {
        $ids = session('wishlist', []);
        return view('wishlist', ['products' => Product::with('category')->whereIn('id', $ids)->get()]);
    }
    public function toggle(Product $product) {
        $ids = session('wishlist', []);
        $active = ! in_array($product->id, $ids);
        $ids = $active ? [...$ids, $product->id] : array_values(array_diff($ids, [$product->id]));
        session(['wishlist' => $ids]);
        $msg = $active ? "Added “{$product->title}” to your wishlist." : 'Removed from your wishlist.';
        if (request()->expectsJson()) return response()->json(['message' => $msg, 'active' => $active, 'wishlist' => count($ids)]);
        return back()->with('status', $msg);
    }
}

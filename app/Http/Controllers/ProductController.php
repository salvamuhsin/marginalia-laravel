<?php
namespace App\Http\Controllers;
use App\Models\{Category, Product};

class ProductController extends Controller {
    public function index(string $type = 'book') {
        $products = Product::with('category')->where('type', $type)
            ->when(request('q'), fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('title', 'like', "%$s%")->orWhere('author', 'like', "%$s%")->orWhere('isbn', 'like', "%$s%")))
            ->when(request('category'), fn ($q, $c) => $q->whereHas('categories', fn ($w) => $w->whereIn('slug', (array) $c)))
            ->when(is_numeric(request('min')), fn ($q) => $q->where('price', '>=', request('min') * 100))
            ->when(is_numeric(request('max')), fn ($q) => $q->where('price', '<=', request('max') * 100))
            ->when(request()->boolean('stock'), fn ($q) => $q->where('stock', '>', 0))
            ->when(true, fn ($q) => match (request('sort')) {
                'price_asc' => $q->orderBy('price'), 'price_desc' => $q->orderByDesc('price'),
                'title' => $q->orderBy('title'), default => $q->latest(),
            })
            ->paginate(12)->withQueryString();

        return view('products.index', [
            'products' => $products, 'type' => $type,
            'categories' => Category::where('type', $type)->orderBy('name')->get(),
        ]);
    }
    public function show(Product $product) {
        $product->load(['category', 'categories']);
        $related = Product::with('category')->where('type', $product->type)
            ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $product->categories->pluck('id')))
            ->whereKeyNot($product->id)->limit(4)->get();
        return view('products.show', compact('product', 'related'));
    }
}

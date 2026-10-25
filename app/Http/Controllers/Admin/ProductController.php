<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Category, OrderItem, Product};
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller {
    // Fallback cover colours, picked automatically when a product has no image.
    private const COLORS = ['#2F5D50', '#7A1F3D', '#14213D', '#8A5A2B', '#2B2D6E', '#3B4A1F', '#B5532A', '#1F3A5F', '#6B2E2E', '#0F5C5C'];

    public function index() {
        $products = Product::with(['category', 'categories'])
            ->when(request('q'), fn ($q, $s) => $q->where(fn ($w) => $w->where('title', 'like', "%$s%")->orWhere('author', 'like', "%$s%")))
            ->when(request('type'), fn ($q, $t) => $q->where('type', $t))
            ->latest()->paginate(15)->withQueryString();
        return view('admin.products.index', compact('products'));
    }
    public function create() { return view('admin.products.form', ['product' => new Product, 'categories' => Category::orderBy('type')->orderBy('name')->get()]); }
    public function edit(Product $product) {
        return view('admin.products.form', ['product' => $product->load('categories'), 'categories' => Category::orderBy('type')->orderBy('name')->get()]);
    }

    private function rules(?Product $p = null): array {
        return [
            'type' => 'required|in:book,accessory',
            'categories' => 'required|array|min:1', 'categories.*' => 'exists:categories,id',
            'title' => 'required|max:200', 'author' => 'nullable|max:200',
            'isbn' => ['nullable', 'max:20', Rule::unique('products', 'isbn')->ignore($p?->id)],
            'description' => 'nullable|max:5000', 'price' => 'required|numeric|min:0', 'stock' => 'required|integer|min:0',
            'year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'image' => 'nullable|image|max:2048',
        ];
    }
    /** Returns [data, category ids] or a redirect back with errors. */
    private function prepare(Request $r, ?Product $p = null) {
        $d = $r->validate($this->rules($p));
        // Accessories have no author / ISBN / year, so always store them empty
        if ($d['type'] === 'accessory') { $d['author'] = null; $d['isbn'] = null; $d['year'] = null; }
        $ids = array_values(array_unique(array_map('intval', $d['categories'])));
        if (Category::whereIn('id', $ids)->where('type', '!=', $d['type'])->exists()) {
            return back()->withInput()->withErrors(['categories' => 'Choose categories that match the product type.']);
        }
        $d['category_id'] = $ids[0];                       // main category = first selected
        $d['price'] = (int) round($d['price'] * 100);
        unset($d['categories'], $d['image']);
        if (! $p) $d['cover_color'] = self::COLORS[crc32($d['title']) % count(self::COLORS)];
        if ($r->hasFile('image')) {
            if ($p?->image) Storage::disk('public')->delete($p->image);
            $d['image'] = $r->file('image')->store('products', 'public');
        } elseif ($p && $r->boolean('remove_image') && $p->image) {
            Storage::disk('public')->delete($p->image); $d['image'] = null;
        }
        return [$d, $ids];
    }
    public function store(Request $r) {
        $res = $this->prepare($r);
        if ($res instanceof RedirectResponse) return $res;
        [$d, $ids] = $res;
        Product::create($d)->categories()->sync($ids);
        return redirect()->route('admin.products.index')->with('status', 'Product added.');
    }
    public function update(Request $r, Product $product) {
        $res = $this->prepare($r, $product);
        if ($res instanceof RedirectResponse) return $res;
        [$d, $ids] = $res;
        $product->update($d);
        $product->categories()->sync($ids);
        return redirect()->route('admin.products.index')->with('status', 'Product updated.');
    }
    public function destroy(Product $product) {
        if (OrderItem::where('product_id', $product->id)->exists()) {
            return back()->with('status', 'This product is part of past orders and cannot be deleted. Set its stock to 0 instead.');
        }
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();
        return back()->with('status', 'Product deleted.');
    }
}

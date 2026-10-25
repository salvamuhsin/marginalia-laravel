<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller {
    public function index() { return view('admin.categories.index', ['categories' => Category::withCount('products')->orderBy('type')->orderBy('name')->paginate(15)->withQueryString()]); }
    public function edit(Category $category) { return view('admin.categories.edit', compact('category')); }

    private function rules(?Category $c = null): array {
        return ['name' => ['required', 'max:80', Rule::unique('categories', 'name')->ignore($c?->id)],
                'type' => 'required|in:book,accessory', 'icon' => 'nullable|max:8'];
    }
    private function slug(string $name, ?int $ignore = null): string {
        $base = $slug = Str::slug($name) ?: 'category'; $n = 2;
        while (Category::where('slug', $slug)->when($ignore, fn ($q) => $q->whereKeyNot($ignore))->exists()) $slug = "$base-" . $n++;
        return $slug;
    }
    public function store(Request $r) {
        $d = $r->validate($this->rules());
        Category::create($d + ['slug' => $this->slug($d['name'])]);
        return back()->with('status', 'Category added.');
    }
    public function update(Request $r, Category $category) {
        $d = $r->validate($this->rules($category));
        $category->update($d + ['slug' => $this->slug($d['name'], $category->id)]);
        return redirect()->route('admin.categories.index')->with('status', 'Category updated.');
    }
    public function destroy(Category $category) {
        if ($category->products()->exists()) return back()->with('status', 'Move or delete the products in this category first.');
        $category->delete();
        return back()->with('status', 'Category deleted.');
    }
}

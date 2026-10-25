@extends('admin.layout')
@section('admin')
<div class="flex flex-wrap justify-between items-center gap-3 mb-4">
  <h1 class="font-serif text-3xl">Products</h1>
  <a href="{{ route('admin.products.create') }}" class="rounded bg-moss text-paper px-4 py-2 font-medium">+ Add product</a>
</div>
<form method="get" class="flex flex-wrap gap-2 mb-4">
  @csrf
  <input name="q" value="{{ request('q') }}" placeholder="Search title or author" class="rounded border border-ink/25 bg-card px-3 py-2">
  <select name="type" class="rounded border border-ink/25 bg-card px-3 py-2">
    <option value="">All types</option>
    <option value="book" @selected(request('type')==='book')>Books</option>
    <option value="accessory" @selected(request('type')==='accessory')>Accessories</option>
  </select>
  <button class="rounded border border-ink/25 px-4">Filter</button>
</form>
<div class="overflow-x-auto bg-card border border-ink/10 rounded-lg">
  <table class="w-full text-sm">
    <thead class="text-left bg-ink/5">
      <tr>
        <th class="p-3">Cover</th>
        <th>Product</th>
        <th>Type</th>
        <th>Categories</th>
        <th>Price</th>
        <th>Stock</th>
        <th class=" text-center">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($products as $p)
      <tr class="border-t border-ink/10">
        <td class="p-3 w-16">@if($p->image)<img src="{{ asset('storage/' . $p->image) }}" alt="" class="h-16 w-12 object-cover rounded">
          @else
          <div class="h-16 w-12 rounded grid place-items-center text-xl" style="background: {{ $p->cover_color }}">{{ $p->category->icon ?: '📦' }}</div>
          @endif
        </td>
        <td class="font-medium">{{ $p->title }}<span class="block text-xs text-ink/60 font-normal">{{ $p->author }}</span></td>
        <td class="capitalize">{{ $p->type }}</td>
        <td>{{ $p->categories->pluck('name')->join(', ') }}</td>
        <td>{{ $p->price_formatted }}</td>
        <td class="{{ $p->stock <= 5 ? 'text-red-600 font-bold' : '' }}">{{ $p->stock }}</td>
        <td class="p-3 whitespace-nowrap text-center"><a href="{{ route('admin.products.edit', $p) }}" class="underline text-moss">Edit</a>
          <form method="post" action="{{ route('admin.products.destroy', $p) }}" class="inline" onsubmit="return confirm('Delete {{ addslashes($p->title) }}?')">
            @csrf @method('DELETE')
            <button class="underline text-red-600 ml-3">Delete</button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="p-6 text-center text-ink/60">No products yet. Add a category first, then add your first product.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="mt-4">{{ $products->links() }}</div>
@endsection

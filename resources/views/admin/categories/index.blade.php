@extends('admin.layout')
@section('admin')
<h1 class="font-serif text-3xl mb-4">Categories</h1>
<form method="post" action="{{ route('admin.categories.store') }}" class="bg-card border border-ink/10 rounded-lg p-4 flex flex-wrap gap-3 items-end mb-6">
  @csrf
  <label class="flex-1 min-w-[160px]">Name
    <input name="name" required class="mt-1 w-full rounded border border-ink/25 bg-paper px-3 py-2">
    @error('name')
      <span class="text-sm text-red-600">{{ $message }}</span>
    @enderror
  </label>
  <label>Type
    <select name="type" class="mt-1 rounded border border-ink/25 bg-paper px-3 py-2">
      <option value="book">Book</option>
      <option value="accessory">Accessory</option>
    </select>
  </label>
  <label>Icon (emoji)
    <input name="icon" maxlength="8" placeholder="📚" class="mt-1 w-24 rounded border border-ink/25 bg-paper px-3 py-2">
  </label>
  <button class="rounded bg-moss text-paper px-5 py-2 font-medium">Add category</button>
</form>
<div class="bg-card border border-ink/10 rounded-lg overflow-x-auto">
  <table class="w-full text-sm">
    <thead class="text-left bg-ink/5">
      <tr>
        <th class="p-3">Name</th>
        <th>Type</th>
        <th>Products</th>
        <th class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($categories as $c)
        <tr class="border-t border-ink/10">
          <td class="p-3">{{ $c->icon }} {{ $c->name }}</td>
          <td class="capitalize">{{ $c->type }}</td>
          <td>{{ $c->products_count }}</td>
          <td class="p-3 text-center whitespace-nowrap">
            <a href="{{ route('admin.categories.edit', $c) }}" class="underline text-moss">Edit</a>
            <form method="post" action="{{ route('admin.categories.destroy', $c) }}" class="inline" onsubmit="return confirm('Delete this category?')">
              @csrf @method('DELETE')
              <button class="underline text-red-600 ml-3">Delete</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="4" class="p-6 text-center text-ink/60">No categories yet. Add one above (for example Fiction or Bookmarks).</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="mt-4">{{ $categories->links() }}</div>
@endsection

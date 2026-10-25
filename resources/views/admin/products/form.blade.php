@extends('admin.layout')
@php $editing = $product->exists; $selected = old('categories', $editing ? $product->categories->pluck('id')->all() : []); @endphp
@section('admin')
<h1 class="font-serif text-3xl mb-4">{{ $editing ? 'Edit product' : 'Add product' }}</h1>
@if($categories->isEmpty())
  <p class="rounded bg-ember/20 p-4">You need a category first. 
    <a href="{{ route('admin.categories.index') }}" class="underline">Create one here</a>.
  </p>
@else
<form method="post" enctype="multipart/form-data" x-data="{ type: '{{ old('type', $product->type ?: 'book') }}' }"
    action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}" class="bg-card border border-ink/10 rounded-lg p-6 grid sm:grid-cols-2 gap-4">
  @csrf @if($editing) @method('PUT') 
  @endif
  @php $in = 'mt-1 w-full rounded border border-ink/25 bg-paper px-3 py-2'; @endphp

  <div class="sm:col-span-2 rounded-lg border border-dashed border-moss/50 p-4">
    <label class="font-bold">Cover image <span class="font-normal text-xs text-ink/60">(jpg or png, max 2 MB. Books look best as portrait, accessories as square)</span>
      <input type="file" name="image" accept="image/*" class="mt-2 block font-normal">
    </label>
    @error('image')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
    <p class="mt-1 text-xs text-ink/60">No image? The shop shows an automatic coloured cover with the title.</p>
    @if($editing && $product->image)
      <div class="mt-3 flex items-center gap-4">
        <img src="{{ asset('storage/' . $product->image) }}" class="h-28 rounded" alt="">
        <label class="text-sm">
          <input type="checkbox" name="remove_image" value="1"> Remove current image
        </label>
      </div>
      @endif
  </div>

  <label>Type
    <select name="type" x-model="type" class="{{ $in }}">
      @foreach(['book' => 'Book', 'accessory' => 'Accessory'] as $v => $l)
      <option value="{{ $v }}">{{ $l }}</option>
      @endforeach</select>
    </label>
  <label>
    <span x-text="type === 'book' ? 'Title' : 'Product name'">Title</span>
    <input name="title" value="{{ old('title', $product->title) }}" required class="{{ $in }}">
    @error('title')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
  </label>

  <fieldset class="sm:col-span-2">
    <legend>Categories <span class="text-xs text-ink/60">(tick one or more)</span></legend>
    <div class="mt-1 flex flex-wrap gap-2">
      @foreach($categories as $cat)
        <label x-show="type === '{{ $cat->type }}'" class="flex items-center gap-2 rounded border border-ink/25 bg-paper px-3 py-1.5">
          <input type="checkbox" name="categories[]" value="{{ $cat->id }}" :disabled="type !== '{{ $cat->type }}'" @checked(in_array($cat->id, $selected))> {{ $cat->icon }} {{ $cat->name }}
        </label>
      @endforeach
      @foreach(['book', 'accessory'] as $t)
        @unless($categories->where('type', $t)->count())
        <p x-show="type === '{{ $t }}'" class="text-sm text-ink/60">No {{ $t }} categories yet. <a href="{{ route('admin.categories.index') }}" class="underline">Add one</a>.</p>
        @endunless
      @endforeach
    </div>
    @error('categories')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
    @error('categories.*')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
  </fieldset>

  <label x-show="type === 'book'" x-cloak>Author
    <input name="author" :disabled="type !== 'book'" value="{{ old('author', $product->author) }}" class="{{ $in }}">
  </label>
  <label x-show="type === 'book'" x-cloak>ISBN
    <input name="isbn" :disabled="type !== 'book'" value="{{ old('isbn', $product->isbn) }}" class="{{ $in }}">@error('isbn')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
  </label>
  <label>Price ({{ config('shop.currency') }})
    <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $editing ? $product->price / 100 : '') }}" required class="{{ $in }}">@error('price')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
  </label>
  <label>Stock
    <input type="number" min="0" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required class="{{ $in }}">
  </label>
  <label x-show="type === 'book'" x-cloak>Year
    <input type="number" name="year" :disabled="type !== 'book'" value="{{ old('year', $product->year) }}" class="{{ $in }}">@error('year')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
  </label>
  <label class="sm:col-span-2">Description
    <textarea name="description" rows="4" class="{{ $in }}">{{ old('description', $product->description) }}</textarea>
  </label>
  <div class="sm:col-span-2 flex gap-3">
    <button class="rounded bg-moss text-paper px-6 py-2.5 font-medium">{{ $editing ? 'Save changes' : 'Add product' }}</button><a href="{{ route('admin.products.index') }}" class="rounded border border-ink/25 px-5 py-2.5">Cancel</a>
  </div>
</form>
@endif
@endsection
  
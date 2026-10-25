@extends('layout')
@php
  $isBook = $type === 'book';
  $route = $isBook ? 'products.index' : 'accessories.index';
  $hasFilters = request()->hasAny(['q', 'category', 'min', 'max', 'stock', 'sort']);
@endphp
@section('title', $isBook ? 'Books' : 'Accessories')
@section('content')
@include('partials.hero', ['img' => $isBook ? 'hero-books.jpg' : 'hero-accessories.jpg', 'title' => $isBook ? 'Our books' : 'Reading accessories',
  'sub' => $isBook ? 'Search and filter the whole collection.' : 'Bookmarks, bookshelves, holders, book lights and more.'])
<div class="max-w-7xl mx-auto px-4 mt-10 grid lg:grid-cols-[260px_1fr] gap-8">
  <aside class="h-fit">
    {{-- Toggle bar: small screens only --}}
    <button type="button" id="filter-toggle"
            aria-expanded="{{ $hasFilters ? 'true' : 'false' }}" aria-controls="filters"
            class="lg:hidden w-full flex items-center justify-between rounded-lg bg-card border border-ink/10 px-4 py-3 font-bold">
      <span>Filters &amp; sort</span>
      <svg id="filter-chevron" class="w-5 h-5 transition-transform {{ $hasFilters ? 'rotate-180' : '' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
      </svg>
    </button>

    <form id="filters" method="get" action="{{ route($route) }}"
          class="{{ $hasFilters ? '' : 'hidden' }} lg:block mt-3 lg:mt-0 bg-card border border-ink/10 rounded-lg p-5 space-y-5">
      <label class="block"><span class="font-bold">Search</span>
        <input name="q" value="{{ request('q') }}" placeholder="{{ $isBook ? 'Title, author or ISBN' : 'Product name' }}" class="mt-1 w-full rounded border border-ink/25 bg-paper px-3 py-2">
      </label>
      <fieldset>
        <legend class="font-bold mb-1">Category</legend>
        @foreach($categories as $c)
          <label class="flex items-center gap-2 py-0.5">
            <input type="checkbox" name="category[]" value="{{ $c->slug }}"
                   @checked(in_array($c->slug, (array) request('category')))
                   class="accent-[rgb(var(--moss))]">
            <span>{{ $c->icon }}</span>
            {{ $c->name }}
          </label>
        @endforeach
      </fieldset>
      <fieldset>
        <legend class="font-bold mb-1">Price</legend>
        <div class="flex gap-2">
          <input type="number" name="min" min="0" value="{{ request('min') }}" placeholder="Min" aria-label="Minimum price" class="w-full rounded border border-ink/25 bg-paper px-2 py-1.5">
          <input type="number" name="max" min="0" value="{{ request('max') }}" placeholder="Max" aria-label="Maximum price" class="w-full rounded border border-ink/25 bg-paper px-2 py-1.5">
        </div>
      </fieldset>
      <label class="flex items-center gap-2">
        <input type="checkbox" name="stock" value="1" @checked(request('stock')) class="accent-[rgb(var(--moss))]"> In stock only
      </label>
      <label class="block"><span class="font-bold">Sort by</span>
        <select name="sort" class="mt-1 w-full rounded border border-ink/25 bg-paper px-2 py-2">
          @foreach(['' => 'Newest', 'title' => 'Title A–Z', 'price_asc' => 'Price: low to high', 'price_desc' => 'Price: high to low'] as $v => $l)
            <option value="{{ $v }}" @selected(request('sort', '') === $v)>{{ $l }}</option>
          @endforeach
        </select>
      </label>
      <div class="flex gap-2">
        <button class="flex-1 rounded bg-moss text-paper py-2 font-medium">Apply</button>
        <a href="{{ route($route) }}" class="rounded border border-ink/25 px-3 py-2">Reset</a>
      </div>
    </form>
  </aside>

  <section>
    <p class="mb-4 text-ink/70">{{ $products->total() }} {{ $isBook ? 'books' : 'products' }} found</p>
    @if($products->isEmpty())<p>Nothing matches your filters. Try resetting them.</p>@else
      <div class="grid grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($products as $p)@include('partials.card', ['p' => $p])
        @endforeach
      </div>
      <div class="mt-8">{{ $products->links() }}</div>
    @endif
  </section>
</div>

<script>
  (function () {
    var btn = document.getElementById('filter-toggle');
    var form = document.getElementById('filters');
    var chevron = document.getElementById('filter-chevron');
    btn.addEventListener('click', function () {
      var open = form.classList.toggle('hidden') === false;
      btn.setAttribute('aria-expanded', open);
      chevron.classList.toggle('rotate-180', open);
    });
  })();
</script>
@endsection
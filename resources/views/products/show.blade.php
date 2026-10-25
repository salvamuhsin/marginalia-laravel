@extends('layout')
@section('title', $product->title)
@section('content')
<div class="max-w-7xl mx-auto px-4 mt-10 grid md:grid-cols-[300px_1fr] gap-10">
  <div class="rounded-lg overflow-hidden shadow-md h-fit">@include('partials.cover', ['p' => $product, 'big' => true])</div>
  <div>
    <p class="text-sm text-ink/60">{{ $product->categories->pluck('name')->join(' · ') }}</p>
    <h1 class="font-serif text-4xl mt-1">{{ $product->title }}</h1>
    @if($product->author)
      <p class="mt-1 text-lg">by {{ $product->author }}</p>
    @endif
    <p class="mt-4 max-w-prose leading-relaxed text-ink/80">{{ $product->description }}</p>
    @if($product->isbn)
      <p class="mt-4 text-sm text-ink/60">ISBN {{ $product->isbn }} · Published {{ $product->year }}</p>
    @endif
    <p class="mt-6 text-3xl font-bold">{{ $product->price_formatted }}</p>
    <div class="mt-4 flex flex-wrap gap-3 items-center">
      @if($product->stock > 0)
        <form method="post" action="{{ route('cart.add', $product) }}" class="flex gap-3 items-center" data-ajax>
          @csrf
          <input type="number" name="qty" value="1" min="1" max="{{ $product->stock }}" aria-label="Quantity" class="w-20 rounded border border-ink/25 bg-paper px-2 py-2">
          <button class="rounded bg-moss text-paper px-6 py-2.5 font-medium">Add to cart</button>
        </form>
        <span class="text-sm text-ink/60">{{ $product->stock }} in stock</span>
      @else
        <span class="font-medium">Sold out</span>
      @endif
      @php $on = in_array($product->id, session('wishlist', [])); @endphp
      <form method="post" action="{{ route('wishlist.toggle', $product) }}" data-ajax>
        @csrf
        <button class="rounded border border-ink/25 px-4 py-2.5"><span class="wl-on {{ $on ? '' : 'hidden' }}">♥ In wishlist</span><span class="wl-off {{ $on ? 'hidden' : '' }}">♡ Add to wishlist</span></button>
      </form>
    </div>
  </div>
</div>
@if($related->isNotEmpty())
  <section class="max-w-7xl mx-auto px-4 mt-14">
    <h2 class="font-serif text-2xl mb-4">You may also like</h2>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach($related as $p)
      @include('partials.card', ['p' => $p])
      @endforeach
    </div>
  </section>
@endif
@endsection

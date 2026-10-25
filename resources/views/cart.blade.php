@extends('layout')
@section('title', 'Your cart')
@section('content')
@include('partials.hero', ['img' => 'hero-cart.jpg', 'title' => 'Your cart'])
<section class="max-w-4xl mx-auto px-4 mt-10">
@forelse($lines as $l)
  <div class="flex flex-wrap items-center gap-4 py-4 border-b border-ink/15">
    <div class="w-16 rounded overflow-hidden">@include('partials.cover', ['p' => $l->product])</div>
    <div class="flex-1 min-w-[160px]">
      <a href="{{ route('products.show', $l->product) }}" class="font-medium">{{ $l->product->title }}</a>
      <p class="text-sm text-ink/60">{{ $l->product->price_formatted }} each</p>
    </div>
    <form method="post" action="{{ route('cart.update', $l->product) }}" class="flex gap-2">
      @csrf @method('PATCH')
      <input type="number" name="qty" value="{{ $l->qty }}" min="0" max="{{ $l->product->stock }}" aria-label="Quantity" class="w-16 rounded border border-ink/25 bg-paper px-2 py-1">
      <button class="text-sm underline">Update</button>
    </form>
    <p class="w-24 text-right font-bold">{{ \App\Support\Cart::money($l->total) }}</p>
    <form method="post" action="{{ route('cart.remove', $l->product) }}">
      @csrf @method('DELETE')
      <button class="text-sm underline text-red-600">Remove</button>
    </form>
  </div>
@empty
  <p>Your cart is empty. <a href="{{ route('products.index') }}" class="underline text-moss">Find a book to read</a>.</p>
@endforelse
@if($lines->isNotEmpty())
  <div class="mt-6 flex items-center justify-end gap-6">
    <p class="text-xl">Total <strong>{{ \App\Support\Cart::money($total) }}</strong></p>
    <a href="{{ route('checkout.show') }}" class="rounded bg-moss text-paper px-6 py-2.5 font-medium">@auth Checkout @else Log in to checkout @endauth</a>
  </div>
@endif
</section>
@endsection

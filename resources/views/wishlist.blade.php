@extends('layout')
@section('title', 'Wishlist')
@section('content')
@include('partials.hero', ['img' => 'hero-wishlist.jpg', 'title' => 'Your wishlist', 'sub' => 'Books and accessories you want to come back to.'])
<section class="max-w-7xl mx-auto px-4 mt-10">
  @if($products->isEmpty())
    <p>Your wishlist is empty. <a href="{{ route('products.index') }}" class="underline text-moss">Browse books</a>.</p>
  @else
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6" data-wishlist-page>
      @foreach($products as $p)
      @include('partials.card', ['p' => $p])
      @endforeach
    </div>
    @endif
</section>
@endsection

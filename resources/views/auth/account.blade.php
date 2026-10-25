@extends('layout')
@section('title', 'My orders')
@section('content')
@include('partials.hero', ['img' => 'hero-account.jpg', 'title' => 'My orders', 'sub' => 'Welcome back, ' . auth()->user()->name . '.'])
<section class="max-w-4xl mx-auto px-4 mt-10 space-y-4">
  @forelse($orders as $o)
    <div class="bg-card border border-ink/10 rounded-lg p-5">
      <div class="flex justify-between">
        <p class="font-bold">Order #{{ $o->id }} · {{ $o->created_at->format('d M Y') }}</p>
        <p class="text-sm uppercase tracking-wide text-moss">{{ str_replace('_', ' ', $o->status) }}</p>
      </div>
      <ul class="mt-2 text-sm text-ink/80">@foreach($o->items as $i)
        <li>{{ $i->quantity }} × {{ $i->product->title }}</li>
        @endforeach</ul>
      <p class="mt-2 font-bold">{{ \App\Support\Cart::money($o->total) }}</p>
    </div>
  @empty
    <p>You have no orders yet.</p>
  @endforelse
</section>
@endsection

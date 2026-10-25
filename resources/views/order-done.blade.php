@extends('layout')
@section('title', 'Order placed')
@section('content')
<div class="max-w-2xl mx-auto px-4 mt-16 text-center">
  <p class="text-6xl">🎉</p>
  <h1 class="font-serif text-4xl mt-4">Thank you, {{ $order->name }}!</h1>
  <p class="mt-3">Order #{{ $order->id }} is {{ $order->payment_status === 'paid' ? 'paid and confirmed' : 'placed (pay on delivery)' }}. A confirmation will go to {{ $order->email }}.</p>
  <p class="mt-2 font-bold">Total: {{ \App\Support\Cart::money($order->total) }}</p>
  <a href="{{ route('products.index') }}" class="inline-block mt-6 rounded bg-moss text-paper px-6 py-2.5 font-medium">Keep browsing</a>
</div>
@endsection

@extends('admin.layout')
@section('admin')
<a href="{{ route('admin.orders.index') }}" class="text-sm underline text-moss">← All orders</a>
<h1 class="font-serif text-3xl my-3">Order #{{ $order->id }}</h1>
<div class="grid lg:grid-cols-2 gap-6">
  <div class="bg-card border border-ink/10 rounded-lg p-5 space-y-1">
    <h2 class="font-bold mb-2">Customer</h2>
    <p>{{ $order->name }}</p>
    <p>{{ $order->email }}</p>
    <p>{{ $order->phone }}</p>
    <p class="whitespace-pre-line">{{ $order->address }}</p>
  </div>
  <form method="post" action="{{ route('admin.orders.update', $order) }}" class="bg-card border border-ink/10 rounded-lg p-5 space-y-3">
    @csrf @method('PUT')
    <h2 class="font-bold">Update order</h2>
    <label class="block">Status
      <select name="status" class="mt-1 w-full rounded border border-ink/25 bg-paper px-3 py-2">
        @foreach($statuses as $s)
          <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
        @endforeach
      </select>
    </label>
    <label class="block">Payment ({{ strtoupper($order->payment_method) }})
      <select name="payment_status" class="mt-1 w-full rounded border border-ink/25 bg-paper px-3 py-2">
        @foreach(['unpaid', 'paid'] as $s)
          <option value="{{ $s }}" @selected($order->payment_status === $s)>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
    </label>
    <button class="rounded bg-moss text-paper px-5 py-2 font-medium">Save</button>
    <p class="text-xs text-ink/60">Cancelling an order puts its items back in stock.</p>
  </form>
</div>
<div class="bg-card border border-ink/10 rounded-lg p-5 mt-6">
  <h2 class="font-bold mb-3">Items</h2>
  @foreach($order->items as $i)
    <p class="flex justify-between py-1"><span>{{ $i->quantity }} × {{ $i->product->title }}</span><span>{{ \App\Support\Cart::money($i->price * $i->quantity) }}</span></p>
  @endforeach
  <p class="flex justify-between border-t border-ink/15 mt-2 pt-2 font-bold"><span>Total</span><span>{{ \App\Support\Cart::money($order->total) }}</span></p>
</div>
@endsection

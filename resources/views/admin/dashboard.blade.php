@extends('admin.layout')
@section('admin')
<h1 class="font-serif text-3xl mb-6">Dashboard</h1>
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
  @foreach($stats as $l => $n)
    <div class="bg-card border border-ink/10 rounded-lg p-4">
      <p class="text-sm text-ink/60">{{ $l }}</p>
      <p class="font-serif text-3xl">{{ $n }}</p>
    </div>
  @endforeach
  <div class="bg-card border border-ink/10 rounded-lg p-4">
    <p class="text-sm text-ink/60">Paid revenue</p>
    <p class="font-serif text-3xl">{{ \App\Support\Cart::money($revenue) }}</p>
  </div>
</div>
<div class="grid lg:grid-cols-2 gap-6 mt-8">
  <div class="bg-card border border-ink/10 rounded-lg p-5">
    <h2 class="font-bold mb-3">Recent orders</h2>
    @forelse($recent as $o)
      <a href="{{ route('admin.orders.show', $o) }}" class="flex justify-between py-1.5 border-b border-ink/10 last:border-0"><span>#{{ $o->id }} · {{ $o->name }}</span><span>{{ \App\Support\Cart::money($o->total) }}</span></a>
    @empty
      <p class="text-ink/60">No orders yet.</p>
      @endforelse
    </div>
  <div class="bg-card border border-ink/10 rounded-lg p-5">
    <h2 class="font-bold mb-3">Low stock (5 or fewer)</h2>
    @forelse($low as $p)
      <a href="{{ route('admin.products.edit', $p) }}" class="flex justify-between py-1.5 border-b border-ink/10 last:border-0"><span>{{ $p->title }}</span><span class="font-bold">{{ $p->stock }}</span></a>
    @empty
      <p class="text-ink/60">Nothing is running low.</p>
      @endforelse
    </div>
</div>
@endsection

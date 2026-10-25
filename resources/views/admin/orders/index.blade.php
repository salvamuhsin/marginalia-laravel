@extends('admin.layout')
@section('admin')
<div class="flex flex-wrap justify-between items-center gap-3 mb-4">
  <h1 class="font-serif text-3xl">Orders</h1>
  <form method="get">
    <select name="status" onchange="this.form.submit()" class="rounded border border-ink/25 bg-card px-3 py-2">
      <option value="">All statuses</option>
      @foreach($statuses as $s)
        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
      @endforeach
    </select>
  </form>
</div>
<div class="bg-card border border-ink/10 rounded-lg overflow-x-auto">
  <table class="w-full text-sm">
    <thead class="text-left bg-ink/5">
      <tr>
        <th class="p-3">Order</th>
        <th>Customer</th>
        <th>Total</th>
        <th>Payment</th>
        <th>Status</th>
        <th>Review</th>
      </tr>
    </thead>
    <tbody>
      @forelse($orders as $o)
        <tr class="border-t border-ink/10">
          <td class="p-3">#{{ $o->id }}<span class="block text-xs text-ink/60">{{ $o->created_at->format('d M Y H:i') }}</span></td>
          <td>{{ $o->name }}</td><td>{{ \App\Support\Cart::money($o->total) }}</td>
          <td>{{ strtoupper($o->payment_method) }} · {{ $o->payment_status }}</td>
          <td class="capitalize">{{ str_replace('_', ' ', $o->status) }}</td>
          <td class="p-3 text-right"><a href="{{ route('admin.orders.show', $o) }}" class="underline text-moss">View</a></td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="p-6 text-center text-ink/60">No orders found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="mt-4">{{ $orders->links() }}</div>
@endsection

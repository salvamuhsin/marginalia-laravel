@extends('layout')
@section('title', 'Checkout')
@section('content')
@include('partials.hero', ['img' => 'hero-checkout.jpg', 'title' => 'Checkout'])
<div class="max-w-5xl mx-auto px-4 mt-10 grid md:grid-cols-2 gap-10">
  <form method="post" action="{{ route('checkout.store') }}" class="space-y-4">
    @csrf
    @foreach(['name' => ['Full name', auth()->user()->name ?? ''], 'email' => ['Email', auth()->user()->email ?? ''], 'phone' => ['Phone', '']] as $f => [$l, $def])
      <label class="block"><span class="font-medium">{{ $l }}</span>
        <input name="{{ $f }}" value="{{ old($f, $def) }}" required class="mt-1 w-full rounded border border-ink/25 bg-paper px-3 py-2">
        @error($f)<span class="text-sm text-red-600">{{ $message }}</span>@enderror
      </label>
    @endforeach
    <label class="block"><span class="font-medium">Delivery address</span>
      <textarea name="address" rows="3" required class="mt-1 w-full rounded border border-ink/25 bg-paper px-3 py-2">{{ old('address') }}</textarea>
      @error('address')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
    </label>
    <fieldset class="space-y-2"><legend class="font-medium">Payment method</legend>
      <label class="flex items-center gap-2 border border-ink/15 rounded p-3 bg-card">
        <input type="radio" name="payment" value="cod" @checked(old('payment', 'cod') === 'cod')> Cash on delivery
      </label>
      <label class="flex items-center gap-2 border border-ink/15 rounded p-3 bg-card">
        <input type="radio" name="payment" value="card" @checked(old('payment') === 'card')> Pay online by card (secure Stripe page)
      </label>
      @error('payment')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
    </fieldset>
    <button class="rounded bg-moss text-paper px-6 py-2.5 font-medium">Place order</button>
  </form>
  <div class="bg-card border border-ink/10 rounded-lg p-6 h-fit">
    <h2 class="font-serif text-xl mb-3">Order summary</h2>
    @foreach($lines as $l)
      <p class="flex justify-between py-1"><span>{{ $l->qty }} × {{ $l->product->title }}</span><span>{{ \App\Support\Cart::money($l->total) }}</span></p>
    @endforeach
    <p class="flex justify-between border-t border-ink/15 mt-3 pt-3 font-bold"><span>Total</span><span>{{ \App\Support\Cart::money($total) }}</span></p>
  </div>
</div>
@endsection

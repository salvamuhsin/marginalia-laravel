@extends('layout')
@section('title', 'Contact')
@section('content')
@include('partials.hero', ['img' => 'hero-contact.jpg', 'title' => 'Contact us', 'sub' => 'Questions about an order or a book? We would love to hear from you.'])
@php $lat = config('shop.lat'); $lng = config('shop.lng'); @endphp
<section class="max-w-7xl mx-auto px-4 mt-14 grid lg:grid-cols-2 gap-10">
  <div class="space-y-6">
    <div class="bg-card border border-ink/10 rounded-lg p-6 space-y-3">
      <h2 class="font-serif text-2xl">Get in touch</h2>
      <p>📍 {{ config('shop.address') }}</p>
      <p>📞 {{ config('shop.phone') }}</p>
      <p>✉️ {{ config('shop.email') }}</p>
      <p>🕘 Mon–Sat, 9:00–18:00</p>
    </div>
    <iframe title="Map" class="w-full h-80 rounded-lg border border-ink/10" loading="lazy"
      src="https://www.openstreetmap.org/export/embed.html?bbox={{ $lng - 0.01 }}%2C{{ $lat - 0.006 }}%2C{{ $lng + 0.01 }}%2C{{ $lat + 0.006 }}&layer=mapnik&marker={{ $lat }}%2C{{ $lng }}">
    </iframe>
  </div>
  <form method="post" action="{{ route('contact.send') }}" class="bg-card border border-ink/10 rounded-lg p-6 space-y-4 h-fit">
    @csrf
    <h2 class="font-serif text-2xl">Send a message</h2>
    @foreach(['name' => 'Your name', 'email' => 'Email', 'subject' => 'Subject'] as $f => $l)
      <label class="block"><span class="font-medium">{{ $l }}</span>
        <input name="{{ $f }}" value="{{ old($f) }}" required class="mt-1 w-full rounded border border-ink/25 bg-paper px-3 py-2">
        @error($f)<span class="text-sm text-red-600">{{ $message }}</span>@enderror
      </label>
    @endforeach
    <label class="block"><span class="font-medium">Message</span>
      <textarea name="message" rows="5" required class="mt-1 w-full rounded border border-ink/25 bg-paper px-3 py-2">{{ old('message') }}</textarea>
      @error('message')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
    </label>
    <button class="rounded bg-moss text-paper px-6 py-2.5 font-medium">Send message</button>
  </form>
</section>
@endsection

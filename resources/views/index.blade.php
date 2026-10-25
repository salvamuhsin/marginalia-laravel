@extends('layout')
@section('content')
@php
$slides = [
 ['img'=>'slide-1.jpg','c1'=>'#2F5D50','c2'=>'#1B2433','t'=>'Find your next favourite book','s'=>'Classics, science, history and tech — hand picked for curious readers.','r'=>'products.index','b'=>'Shop books'],
 ['img'=>'slide-2.jpg','c1'=>'#8A5A2B','c2'=>'#1B2433','t'=>'Make reading time cosier','s'=>'Bookmarks, shelves, holders and lights for every reading nook.','r'=>'accessories.index','b'=>'Shop accessories'],
 ['img'=>'slide-3.jpg','c1'=>'#7A1F3D','c2'=>'#14213D','t'=>'New readers get 10% off','s'=>'Subscribe to our newsletter and get your welcome code.','r'=>'home','b'=>'Get the offer'],
];
@endphp
<section x-data="{i:0, n:{{ count($slides) }}, init(){ setInterval(()=>this.i=(this.i+1)%this.n, 6000) }}" class="relative" aria-roledescription="carousel">
  @foreach($slides as $k => $s)
    <div x-show="i==={{ $k }}" @if($k > 0) x-cloak @endif x-transition.opacity.duration.500ms class="relative overflow-hidden min-h-[420px] md:min-h-[580px] flex items-center bg-ink text-white">
      <div class="kb absolute inset-0 bg-cover bg-[position:75%_center] lg:bg-center"
           style="background-image: linear-gradient(90deg, rgb(0 0 0 / .65), rgb(0 0 0 / .1)), url('/images/{{ $s['img'] }}'), linear-gradient(135deg, {{ $s['c1'] }}, {{ $s['c2'] }})"></div>
      <div class="absolute inset-0 bg-black/35 lg:hidden"></div>
      <div class="relative max-w-7xl mx-auto px-4 w-full">
        <h2 style="--d:100ms" class="hero-rise font-serif text-4xl md:text-6xl max-w-2xl leading-tight">{{ $s['t'] }}</h2>
        <p style="--d:300ms" class="hero-rise mt-4 text-lg max-w-lg text-white/90">{{ $s['s'] }}</p>
        <a style="--d:500ms" href="{{ route($s['r']) }}" class="hero-rise inline-block mt-6 rounded bg-ember text-ink px-6 py-3 font-bold">{{ $s['b'] }}</a>
      </div>
    </div>
  @endforeach
  <button @click="i=(i-1+n)%n" class="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-black/40 text-white w-10 h-10" aria-label="Previous slide">‹</button>
  <button @click="i=(i+1)%n" class="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-black/40 text-white w-10 h-10" aria-label="Next slide">›</button>
  <div class="absolute bottom-4 inset-x-0 flex justify-center gap-2">
    @foreach($slides as $k => $s)
      <button @click="i={{ $k }}" :class="i==={{ $k }} ? 'bg-white w-7' : 'bg-white/40 w-2.5'" class="h-2.5 rounded-full transition-all duration-300" aria-label="Slide {{ $k + 1 }}"></button>
    @endforeach
  </div>
</section>

<div class="bg-moss text-paper overflow-hidden py-3" aria-label="Announcements">
  <div class="marquee flex w-max whitespace-nowrap font-medium">
    @for($r = 0; $r < 2; $r++)
      @foreach(['New arrivals every week', 'Free gift wrapping on request', 'Pay online or cash on delivery', 'Reading accessories now in stock', '10% off your first order'] as $m)
        <span class="px-6">{{ $m }}</span><span class="px-2" aria-hidden="true">✦</span>
      @endforeach
    @endfor
  </div>
</div>

<section class="max-w-7xl mx-auto px-4 mt-16">
  <h2 class="font-serif text-3xl mb-6">What we offer</h2>
  <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
    @foreach([
      ['📚','Books','Fiction, science, history and technology titles.','products.index'],
      ['🔖','Accessories','Bookmarks, shelves, holders and book lights.','accessories.index'],
      ['🚚','Fast delivery','Carefully packed and sent to your door.','contact'],
      ['🔒','Easy checkout','Pay online by card or cash on delivery.','cart.index']] as [$i,$t,$d,$r])
      <a href="{{ route($r) }}" class="bg-card border border-ink/10 rounded-lg p-6 hover:border-moss transition">
        <span class="text-4xl">{{ $i }}</span><h3 class="font-serif text-xl mt-3">{{ $t }}</h3><p class="text-ink/70 mt-1 text-sm">{{ $d }}</p></a>
    @endforeach
  </div>
</section>


@if($categories->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 mt-16">
  <div class="flex justify-between items-end mb-6">
    <h2 class="font-serif text-3xl">Shop by category</h2>
    <a href="{{ route('products.index') }}"
       class="text-moss font-medium hover:underline">
      View all →
    </a>
  </div>

  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
    @foreach($categories->take(8) as $cat)
      <a href="{{ route($cat->type === 'book' ? 'products.index' : 'accessories.index', ['category' => [$cat->slug]]) }}"
         class="flex items-center gap-4 bg-card border border-ink/10 rounded-lg p-4 hover:border-moss transition">
        <span class="text-3xl">{{ $cat->icon ?: '📦' }}</span>
        <span>
          <span class="block font-medium">{{ $cat->name }}</span>
          <span class="text-sm text-ink/60">
            {{ $cat->products_count }}
            {{ \Illuminate\Support\Str::plural('item', $cat->products_count) }}
          </span>
        </span>
      </a>
    @endforeach
  </div>
</section>
@endif


@if($books->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 mt-16">
  <div class="flex justify-between items-end mb-6">
    <h2 class="font-serif text-3xl">New books</h2>
    <a href="{{ route('products.index') }}" class="text-moss font-medium">View all →</a>
  </div>
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach($books as $p)
    @include('partials.card', ['p' => $p])
    @endforeach
  </div>
</section>
@endif

<section class="max-w-7xl mx-auto px-4 mt-16">
  <div class="relative overflow-hidden rounded-2xl min-h-[260px] md:min-h-[320px] flex items-center bg-cover bg-[position:75%_center] lg:bg-center text-[#1B2433]"
       style="background-image: url('/images/promo-offer.jpg'), linear-gradient(135deg, #FFF1C9, #FF9E7D)">
    <div class="absolute inset-0 bg-white/55 lg:hidden"></div>
    <div class="relative p-8 md:p-12 max-w-xl">
      <p class="text-[#B5471F] font-bold tracking-widest text-sm">WELCOME OFFER</p>
      <h2 class="font-serif text-3xl md:text-5xl mt-2 leading-tight">Get 10% off your first order</h2>
      <p class="mt-3 text-[#1B2433]/80">Join our newsletter and start your reading list with a little extra.</p>
      <a href="#newsletter" class="inline-block mt-6 rounded bg-[#1B2433] text-white px-6 py-3 font-bold">Join the newsletter</a>
    </div>
  </div>
</section>

<section class="mt-16 bg-card border-y border-ink/10">
  <div class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="font-serif text-3xl text-center">Why readers choose us</h2>
    <div class="mt-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-4 text-center">
      @foreach([['🚚','Fast delivery','Orders are packed with care and sent to your door.'],['🔒','Secure checkout','Pay by card online or with cash on delivery.'],['🎁','Gift ready','Books and accessories that make thoughtful presents.'],['💬','Friendly support','Real people answer your questions quickly.']] as [$ico, $ttl, $txt])
        <div>
          <p class="text-4xl">{{ $ico }}</p>
          <h3 class="font-serif text-xl mt-3">{{ $ttl }}</h3>
          <p class="text-ink/70 text-sm mt-1">{{ $txt }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>


<section class="max-w-7xl mx-auto px-4 mt-16">
  <h2 class="font-serif text-3xl mb-6 text-center">Special offers</h2>
  <div class="grid gap-6 md:grid-cols-3">
    @foreach([
      ['10% off', 'your first order', 'WELCOME10', 'offer-welcome.jpg'],
      ['Free bookmark', 'with every 3 books', 'BOOKMARK', 'offer-bookmark.jpg'],
      ['Free shipping', 'on orders over ' . config('shop.currency') . '50', 'SHIPFREE', 'offer-shipping.jpg'],
    ] as [$a, $b, $code, $img])
      <div class="relative overflow-hidden rounded-lg min-h-[280px] flex items-end bg-cover bg-center"
           style="background-image: url('{{ asset('images/' . $img) }}');">

        {{-- Dark gradient: strongest at the bottom where the text sits --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/50 to-black/10"></div>

        <div class="relative z-10 p-8 text-white w-full">
          <p class="font-serif text-3xl drop-shadow-md">{{ $a }}</p>
          <p class="mt-1 text-white/90 drop-shadow">{{ $b }}</p>
          <p class="mt-4 inline-block border border-dashed border-white/80 bg-black/30 rounded px-3 py-1 text-sm tracking-widest">
            {{ $code }}
          </p>
        </div>
      </div>
    @endforeach
  </div>
</section>

<section class="max-w-7xl mx-auto px-4 mt-16">
  <div class="relative overflow-hidden rounded-2xl min-h-[260px] md:min-h-[320px] flex items-center bg-cover bg-[position:75%_center] lg:bg-center text-[#1B2433]"
       style="background-image: url('/images/promo-accessories.jpg'), linear-gradient(135deg, #D8F3E5, #9AD0F5)">
    <div class="absolute inset-0 bg-white/55 lg:hidden"></div>
    <div class="relative p-8 md:p-12 max-w-xl">
      <p class="text-[#0F5C5C] font-bold tracking-widest text-sm">READING ACCESSORIES</p>
      <h2 class="font-serif text-3xl md:text-5xl mt-2 leading-tight">Make every page cosier</h2>
      <p class="mt-3 text-[#1B2433]/80">Bookmarks, book lights, shelves and holders for your reading corner.</p>
      <a href="{{ route('accessories.index') }}" class="inline-block mt-6 rounded bg-[#1B2433] text-white px-6 py-3 font-bold">Shop accessories</a>
    </div>
  </div>
</section>

@if($accessories->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 mt-16">
  <div class="flex justify-between items-end mb-6">
    <h2 class="font-serif text-3xl">Reading accessories</h2>
    <a href="{{ route('accessories.index') }}" class="text-moss font-medium">View all →</a>
  </div>
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach($accessories as $p)
    @include('partials.card', ['p' => $p])
    @endforeach
  </div>
</section>
@endif

<section class="max-w-7xl mx-auto px-4 mt-16">
  <h2 class="font-serif text-3xl mb-6 text-center">What our readers say</h2>
  <div class="grid gap-6 md:grid-cols-3">
    @foreach([['Sara M.','Great selection and my order arrived beautifully packed. The reading light is perfect for bedtime.'],['Omar K.','Found a book I had been searching for months. Easy checkout and quick delivery.'],['Lina A.','Love the bookmarks and the wooden book stand. I will be ordering again.']] as [$who, $quote])
      <figure class="bg-card border border-ink/10 rounded-lg p-6">
        <p class="text-ember text-lg" aria-label="5 out of 5 stars">★★★★★</p>
        <blockquote class="mt-3 text-ink/80">“{{ $quote }}”</blockquote>
        <figcaption class="mt-4 font-medium">{{ $who }}</figcaption>
      </figure>
    @endforeach
  </div>
</section>

<section class="max-w-7xl mx-auto px-4 mt-16">
  <h2 class="font-serif text-3xl mb-6 text-center">How it works</h2>
  <div class="grid gap-6 md:grid-cols-3">
    @foreach([['Browse and search','Filter by category or price and find your next read or reading accessory.'],['Add to cart','Save favourites to your wishlist and add what you want to your cart.'],['Checkout and enjoy','Log in, choose card or cash on delivery, and we deliver to your door.']] as $i => [$ttl, $txt])
      <div class="flex gap-4">
        <span class="shrink-0 w-10 h-10 rounded-full bg-moss text-paper grid place-items-center font-bold">{{ $i + 1 }}</span>
        <div>
          <h3 class="font-serif text-xl">{{ $ttl }}</h3>
          <p class="text-ink/70 mt-1">{{ $txt }}</p>
        </div>
      </div>
    @endforeach
  </div>
</section>

@php
$faqs = [
  ['Do I need an account to order?', 'You can browse and fill your cart as a guest. To place an order you need to log in or create a free account.'],
  ['Which payment methods do you accept?', 'You can pay online by card through a secure payment page, or choose cash on delivery.'],
  ['Where can I see my orders?', 'Log in and open “My orders” from the profile menu to see every order and its status.'],
  ['What if an item is sold out?', 'Sold-out items are marked on the card. Add them to your wishlist and check back soon.'],
];
@endphp
<section x-data="{ open: null }" class="max-w-3xl mx-auto px-4 mt-16">
  <h2 class="font-serif text-3xl mb-6 text-center">Frequently asked questions</h2>
  <div class="space-y-3">
    @foreach($faqs as $k => [$q, $a])
      <div class="bg-card border border-ink/10 rounded-lg">
        <button type="button" @click="open = open === {{ $k }} ? null : {{ $k }}" :aria-expanded="open === {{ $k }}" class="w-full flex justify-between gap-4 text-left p-4 font-medium">
          <span>{{ $q }}</span><span x-text="open === {{ $k }} ? '−' : '+'" aria-hidden="true"></span>
        </button>
        <p x-show="open === {{ $k }}" x-cloak x-transition class="px-4 pb-4 text-ink/80">{{ $a }}</p>
      </div>
    @endforeach
  </div>
</section>

<section id="newsletter" class="max-w-7xl mx-auto px-4 mt-16 scroll-mt-24">
  <div class="rounded-2xl bg-moss text-paper p-8 md:p-12 flex flex-col items-center text-center md:flex-row md:text-left md:justify-between gap-6 md:gap-8">
    <img src="{{ asset('images/newsletter-illustration.svg') }}" alt="" class="w-32 sm:w-40 md:w-44 shrink-0">
    <div class="flex-1">
      <h2 class="font-serif text-3xl">Join our newsletter</h2>
      <p class="mt-2 text-paper/80">New arrivals, offers and reading lists, once a month.</p>
    </div>
    <form method="post" action="{{ route('subscribe') }}" class="flex gap-2 w-full md:w-auto">
      @csrf
      <input type="email" name="email" required placeholder="you@example.com" aria-label="Email" class="flex-1 min-w-0 md:w-72 rounded px-3 py-2.5 bg-paper text-ink">
      <button class="rounded bg-ember text-ink px-5 font-bold">Subscribe</button>
    </form>
  </div>
  @error('email')<p class="text-sm text-red-600 mt-2">{{ $message }}</p>@enderror
</section>
@endsection

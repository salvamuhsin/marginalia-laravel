@extends('layout')
@section('title', 'About us')
@section('content')
@include('partials.hero', ['img' => 'hero-about.jpg', 'title' => 'About us', 'sub' => 'A small bookshop with a big love for reading.'])

<section class="max-w-7xl mx-auto px-4 mt-14 grid md:grid-cols-2 gap-10 items-center">
  <div class="aspect-[4/3] rounded-lg bg-cover bg-center" style="background-image: url('/images/about-story.jpg'), linear-gradient(135deg, #2F5D50, #8A5A2B)" role="img" aria-label="Our bookshop"></div>
  <div>
    <h2 class="font-serif text-3xl">Our story</h2>
    <p class="mt-4 leading-relaxed text-ink/80">Marginalia started with a simple idea: reading is better when the right book meets the right setting. We stock carefully chosen books across fiction, science, history and technology, plus the bookmarks, shelves, holders and lights that make reading a pleasure.</p>
    <p class="mt-4 leading-relaxed text-ink/80">What began as one shelf of favourite titles has grown into a shop for readers who care about both what they read and where they read it. Every book and product on our shelves is one we would happily give to a friend.</p>
    <a href="{{ route('products.index') }}" class="inline-block mt-6 rounded bg-moss text-paper px-6 py-2.5 font-medium">Browse our books</a>
  </div>
</section>

<section class="max-w-7xl mx-auto px-4 mt-14 grid md:grid-cols-2 gap-6">
  <div class="bg-card border border-ink/10 rounded-lg p-8">
    <p class="text-3xl">🎯</p>
    <h2 class="font-serif text-2xl mt-2">Our mission</h2>
    <p class="mt-2 text-ink/80 leading-relaxed">To make good books easy to find and reading a daily habit, by offering a friendly, honest and affordable shop for every kind of reader.</p>
  </div>
  <div class="bg-card border border-ink/10 rounded-lg p-8">
    <p class="text-3xl">🔭</p>
    <h2 class="font-serif text-2xl mt-2">Our vision</h2>
    <p class="mt-2 text-ink/80 leading-relaxed">A community where every home has a bookshelf, every reader has a favourite corner, and every gift can be a story.</p>
  </div>
</section>

<section class="max-w-7xl mx-auto px-4 mt-14 grid sm:grid-cols-3 gap-6 text-center">
  @foreach([['12,000+','Happy readers'],['500+','Titles in stock'],['4.9/5','Average review']] as [$num, $lbl])
    <div class="bg-card border border-ink/10 rounded-lg p-8">
      <p data-count class="font-serif text-4xl text-moss">{{ $num }}</p>
      <p class="text-ink/70 mt-1">{{ $lbl }}</p>
    </div>
  @endforeach
</section>

<section class="max-w-7xl mx-auto px-4 mt-14">
  <h2 class="font-serif text-3xl text-center">What we believe in</h2>
  <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
    @foreach([['📚','Quality first','We choose every title and product by hand.'],['🤝','Honest service','Clear prices, clear delivery, no surprises.'],['🌱','Care for the planet','We reuse and recycle packaging wherever we can.'],['❤️','Love of reading','We are readers first and shopkeepers second.']] as [$ico, $ttl, $txt])
      <div class="bg-card border border-ink/10 rounded-lg p-6">
        <p class="text-4xl">{{ $ico }}</p>
        <h3 class="font-serif text-xl mt-3">{{ $ttl }}</h3>
        <p class="text-ink/70 text-sm mt-1">{{ $txt }}</p>
      </div>
    @endforeach
  </div>
</section>

<section class="max-w-4xl mx-auto px-4 mt-14">
  <h2 class="font-serif text-3xl text-center">Our journey</h2>
  <ol class="mt-8 border-l-2 border-moss/40 ml-3 space-y-8">
    @foreach([['The beginning','One small shelf and a handful of favourite books.'],['Growing shelves','More categories, more readers and our first reading accessories.'],['Going online','Our website opened so readers everywhere could shop with us.'],['Today','Books and accessories delivered to homes, with plenty more to come.']] as [$ttl, $txt])
      <li class="ml-6 relative"><span class="absolute -left-[33px] top-1 w-4 h-4 rounded-full bg-moss ring-4 ring-paper"></span>
        <h3 class="font-serif text-xl">{{ $ttl }}</h3>
        <p class="text-ink/70 mt-1">{{ $txt }}</p>
      </li>
    @endforeach
  </ol>
</section>

<section class="max-w-7xl mx-auto px-4 mt-14">
  <h2 class="font-serif text-3xl text-center">Meet the team</h2>
  <div class="mt-8 grid grid-cols-2 lg:grid-cols-4 gap-6 text-center">
    @foreach([['Amina R.','Founder','#2F5D50'],['Daniel K.','Head of Books','#8A5A2B'],['Leila H.','Customer Care','#7A1F3D'],['Yusuf T.','Deliveries','#1F3A5F']] as [$nm, $role, $col])
      <div>
        <div class="mx-auto w-24 h-24 rounded-full grid place-items-center text-white font-serif text-3xl" style="background: {{ $col }}">{{ substr($nm, 0, 1) }}</div>
        <p class="font-medium mt-3">{{ $nm }}</p>
        <p class="text-sm text-ink/60">{{ $role }}</p>
      </div>
    @endforeach
  </div>
</section>

<section class="max-w-7xl mx-auto px-4 mt-14">
  <div class="rounded-2xl bg-moss text-paper p-8 md:p-12 text-center">
  <img src="{{ asset('images/contact-illustration.svg') }}" alt="" class="mx-auto w-40 md:w-52 mb-4">
  <h2 class="font-serif text-3xl">Come say hello</h2>
    <p class="mt-2 text-paper/80 max-w-xl mx-auto">Questions, book requests or just want to talk books? We would love to hear from you.</p>
    <a href="{{ route('contact') }}" class="inline-block mt-6 rounded bg-ember text-ink px-6 py-3 font-bold">Contact us</a>
  </div>
</section>
@endsection

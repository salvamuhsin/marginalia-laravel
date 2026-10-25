<section class="bg-cover bg-center text-white" style="background-image: linear-gradient(rgb(0 0 0 / .5), rgb(0 0 0 / .5)), url('/images/{{ $img }}'), linear-gradient(135deg, #2F5D50, #1B2433)">
  <div class="max-w-7xl mx-auto px-4 py-16 md:py-24">
    <h1 class="hero-rise font-serif text-4xl md:text-5xl">{{ $title }}</h1>
    @isset($sub)<p style="--d:150ms" class="hero-rise mt-3 max-w-xl text-white/85 text-lg">{{ $sub }}</p>@endisset
  </div>
</section>

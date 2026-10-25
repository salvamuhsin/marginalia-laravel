<!doctype html>
<html lang="en" data-theme="light">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', config('shop.name'))</title>
  <script>document.documentElement.dataset.theme = localStorage.getItem('theme') || 'light';</script>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Young+Serif&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <script>
    tailwind.config = { theme: { extend: {
      fontFamily: { serif: ['"Young Serif"', 'serif'], sans: ['"DM Sans"', 'sans-serif'] },
      colors: { paper: 'rgb(var(--paper) / <alpha-value>)', ink: 'rgb(var(--ink) / <alpha-value>)',
                moss: 'rgb(var(--moss) / <alpha-value>)', ember: 'rgb(var(--ember) / <alpha-value>)',
                card: 'rgb(var(--card) / <alpha-value>)' } } } }
  </script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
  <style>
    :root { --paper: 245 246 242; --ink: 27 36 51; --moss: 47 93 80; --ember: 217 130 43; --card: 255 255 255; }
    [data-theme=dark] { --paper: 15 20 29; --ink: 232 235 240; --moss: 110 180 160; --ember: 240 160 80; --card: 27 35 49; }
    [x-cloak] { display: none !important; }
    .marquee { animation: scroll 35s linear infinite; }
    .marquee:hover { animation-play-state: paused; }
    @keyframes scroll { to { transform: translateX(-50%); } }
    @media (prefers-reduced-motion: reduce) { .marquee { animation: none; } }

    /* ===== Scroll animations ===== */
    html { scroll-behavior: smooth; }
    main { animation: pagein .5s ease both; }                       /* opacity only: never breaks sticky/fixed children */
    @keyframes pagein { from { opacity: 0 } to { opacity: 1 } }

    /* reveal on scroll (classes are added by JS, removed again once finished) */
    .reveal { opacity: 0; transform: translate3d(0, 30px, 0); will-change: opacity, transform;
              transition: opacity .8s cubic-bezier(.22,.61,.36,1) var(--d,0ms), transform .8s cubic-bezier(.22,.61,.36,1) var(--d,0ms) !important; }
    .reveal[data-reveal=left]  { transform: translate3d(-36px, 0, 0); }
    .reveal[data-reveal=right] { transform: translate3d(36px, 0, 0); }
    .reveal[data-reveal=zoom]  { transform: scale(.95); }
    .reveal.is-in { opacity: 1; transform: none; }

    /* scroll progress bar */
    #scrollbar { position: fixed; top: 0; left: 0; width: 100%; height: 3px; z-index: 60; pointer-events: none;
                 transform: scaleX(0); transform-origin: 0 50%;
                 background: linear-gradient(90deg, rgb(var(--moss)), rgb(var(--ember))); }

    /* header gets a soft shadow once you scroll */
    header.sticky { transition: box-shadow .3s ease; }
    header.is-scrolled { box-shadow: 0 10px 30px -16px rgb(0 0 0 / .35); }

    /* back to top */
    #to-top { position: fixed; right: 1.25rem; bottom: 1.25rem; z-index: 40; width: 2.75rem; height: 2.75rem; border-radius: 9999px;
              display: grid; place-items: center; font-size: 1.25rem; line-height: 1; color: rgb(var(--paper)); background: rgb(var(--moss));
              box-shadow: 0 8px 20px -6px rgb(0 0 0 / .4); opacity: 0; transform: translateY(16px); pointer-events: none;
              transition: opacity .3s, transform .3s; }
    #to-top.is-on { opacity: 1; transform: none; pointer-events: auto; }
    #to-top:hover { transform: translateY(-3px); }

    /* hero: Ken Burns zoom + staggered text (restarts each time a slide is shown) */
    @keyframes kb   { from { transform: scale(1) } to { transform: scale(1.12) } }
    @keyframes rise { from { opacity: 0; transform: translateY(26px) } to { opacity: 1; transform: none } }
    .kb { animation: kb 9s ease-out forwards; transform-origin: 70% 50%; }
    .hero-rise { animation: rise .9s cubic-bezier(.22,.61,.36,1) backwards; animation-delay: var(--d, 0ms); }

    /* micro-interactions */
    main a.bg-card { transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease; }
    main a.bg-card:hover { transform: translateY(-5px); box-shadow: 0 16px 30px -18px rgb(0 0 0 / .35); }
    main a.bg-card > span:first-child { display: inline-block; transition: transform .35s cubic-bezier(.34,1.56,.64,1); }
    main a.bg-card:hover > span:first-child { transform: scale(1.18) rotate(-6deg); }
    main a.inline-block, main form button, #to-top { transition-property: transform, box-shadow, filter, opacity; transition-duration: .2s; }
    main a.inline-block:hover { transform: translateY(-2px); box-shadow: 0 10px 20px -10px rgb(0 0 0 / .45); }
    main a.inline-block:active, main form button:active { transform: scale(.96); }
    @keyframes bump  { 40% { transform: scale(1.55) } 100% { transform: scale(1) } }
    .bump { animation: bump .45s ease; transform-box: fill-box; transform-origin: center; }
    @keyframes float { 50% { transform: translateY(-10px) } }
    img[src*="illustration"] { animation: float 5s ease-in-out infinite; }
    [data-count] { font-variant-numeric: tabular-nums; }

    @media (prefers-reduced-motion: reduce) {
      html { scroll-behavior: auto; }
      main, .kb, .hero-rise, .bump, img[src*="illustration"] { animation: none !important; }
      .reveal { opacity: 1 !important; transform: none !important; transition: none !important; }
    }
  </style>
</head>
<body class="bg-paper text-ink font-sans antialiased min-h-dvh flex flex-col overflow-x-clip">
<div id="scrollbar" aria-hidden="true"></div>
@php $wl = count(session('wishlist', [])); $ct = \App\Support\Cart::count(); @endphp
<header class="sticky top-0 z-40 bg-card/95 backdrop-blur border-b border-ink/10" x-data="{menu:false, search:false, user:false}">
  <div class="max-w-7xl mx-auto px-4 h-16 flex items-center gap-4">
    <button class="lg:hidden" @click="menu=!menu" aria-label="Menu">@include('partials.icon', ['ic_name' => 'menu'])</button>
    <a href="{{ route('home') }}" class="font-serif text-2xl text-moss">Marginalia</a>
    <nav class="hidden lg:flex gap-6 flex-1 ml-6 font-medium">
      @foreach([['home','Home'],['about','About'],['products.index','Books'],['accessories.index','Accessories'],['contact','Contact']] as [$r,$l])
        <a href="{{ route($r) }}" class="py-1 {{ request()->routeIs($r) ? 'text-moss border-b-2 border-moss' : 'hover:text-moss' }}">{{ $l }}</a>
      @endforeach
    </nav>
    <div class="flex items-center gap-3 ml-auto lg:ml-0">
      <button @click="search=!search; $nextTick(()=>$refs.q.focus())" aria-label="Search">@include('partials.icon', ['ic_name' => 'search'])</button>
      <button onclick="const t=document.documentElement.dataset.theme==='dark'?'light':'dark';document.documentElement.dataset.theme=t;localStorage.setItem('theme',t)" aria-label="Toggle dark mode" class="text-lg leading-none">◐</button>
      <a href="{{ route('wishlist.index') }}" class="relative" aria-label="Wishlist">@include('partials.icon', ['ic_name' => 'heart'])
        <span id="wl-badge" class="absolute -top-2 -right-2 text-[10px] bg-ember text-paper rounded-full px-1.5 {{ $wl ? '' : 'hidden' }}">{{ $wl }}</span></a>
      <a href="{{ route('cart.index') }}" class="relative" aria-label="Cart">@include('partials.icon', ['ic_name' => 'bag'])
        <span id="cart-badge" class="absolute -top-2 -right-2 text-[10px] bg-ember text-paper rounded-full px-1.5 {{ $ct ? '' : 'hidden' }}">{{ $ct }}</span></a>
      <div class="relative">
        <button @click="user=!user" @click.outside="user=false" aria-label="Profile">@include('partials.icon', ['ic_name' => 'user'])</button>
        <div x-show="user" x-cloak class="absolute right-0 mt-3 w-48 bg-card border border-ink/10 rounded shadow-lg py-2 text-sm">
          @auth
            <p class="px-4 py-1 text-ink/60">Hi, {{ auth()->user()->name }}</p>
            @if(auth()->user()->is_admin)<a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 font-bold text-moss hover:bg-ink/5">Admin panel</a>@endif
            <a href="{{ route('account') }}" class="block px-4 py-2 hover:bg-ink/5">My orders</a>
            <form method="post" action="{{ route('logout') }}">@csrf<button class="w-full text-left px-4 py-2 hover:bg-ink/5">Log out</button></form>
          @else
            <a href="{{ route('login') }}" class="block px-4 py-2 hover:bg-ink/5">Log in</a>
            <a href="{{ route('register') }}" class="block px-4 py-2 hover:bg-ink/5">Create account</a>
          @endauth
        </div>
      </div>
    </div>
  </div>
  <div x-show="search" x-cloak x-transition class="border-t border-ink/10 bg-card">
    <form action="{{ route('products.index') }}" class="max-w-7xl mx-auto px-4 py-3 flex gap-2" role="search">
      @csrf
      <input x-ref="q" name="q" placeholder="Search books by title, author or ISBN" class="flex-1 rounded border border-ink/25 bg-paper px-3 py-2">
      <button class="rounded bg-moss text-paper px-5 font-medium">Search</button>
    </form>
  </div>
  <nav x-show="menu" x-cloak class="lg:hidden border-t border-ink/10 bg-card px-4 py-2 flex flex-col">
    @foreach([['home','Home'],['about','About'],['products.index','Books'],['accessories.index','Accessories'],['contact','Contact']] as [$r,$l])
      <a href="{{ route($r) }}" class="py-2 font-medium">{{ $l }}</a>
    @endforeach
  </nav>
</header>

@if(session('status'))
  <p class="max-w-7xl mx-auto mt-4 px-4"><span class="block rounded bg-moss/15 border border-moss/30 text-moss px-4 py-3" role="status">{{ session('status') }}</span></p>
@endif
<main class="flex-1">@yield('content')</main>

<footer class="bg-ink text-paper mt-20">
  <div class="max-w-7xl mx-auto px-4 py-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">

    <div>
      <p class="font-serif text-2xl">Marginalia</p><p class="mt-3 text-paper/70 text-sm">Books and reading accessories for people who love a good page.</p>
    </div>
    <div>
      <p class="font-bold mb-3">Explore</p>
      <ul class="space-y-2 text-sm text-paper/80">@foreach([['home','Home'],['about','About us'],['products.index','Books'],['accessories.index','Accessories'],['contact','Contact']] as [$r,$l])<li><a href="{{ route($r) }}">{{ $l }}</a></li>@endforeach</ul>
    </div>
    <div>
      <p class="font-bold mb-3">Account</p>
      <ul class="space-y-2 text-sm text-paper/80">
        <li><a href="{{ route('account') }}">My orders</a></li>
        <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
        <li><a href="{{ route('cart.index') }}">Cart</a></li>
      </ul>
    </div>
    <div>
      <p class="font-bold mb-3">Contact</p>
      <ul class="space-y-2 text-sm text-paper/80">
        <li>{{ config('shop.address') }}</li>
        <li>{{ config('shop.phone') }}</li>
        <li>{{ config('shop.email') }}</li>
      </ul>
    </div>
  </div>
  <p class="border-t border-paper/15 text-center text-xs text-paper/60 py-4">© {{ date('Y') }} {{ config('shop.name') }}. All rights reserved.</p>
</footer>
<button id="to-top" type="button" aria-label="Back to top">↑</button>
<div id="toast" class="fixed bottom-5 left-1/2 -translate-x-1/2 z-50 max-w-[90vw] bg-ink text-paper px-5 py-3 rounded-lg shadow-lg text-sm opacity-0 pointer-events-none transition-opacity duration-300" role="status" aria-live="polite"></div>
<script>
// Add to cart / wishlist without reloading the page (forms marked with data-ajax)
(() => {
  const toast = document.getElementById('toast'); let timer;
  const say = m => { toast.textContent = m; toast.classList.remove('opacity-0'); clearTimeout(timer); timer = setTimeout(() => toast.classList.add('opacity-0'), 2600); };
  const bump = el => { el.classList.remove('bump'); void el.offsetWidth; el.classList.add('bump'); };
  const badge = (id, n) => { const b = document.getElementById(id); if (b) { b.textContent = n; b.classList.toggle('hidden', !n); bump(b); } };
  document.addEventListener('submit', async e => {
    const f = e.target.closest('form[data-ajax]'); if (!f) return;
    e.preventDefault();
    const btn = f.querySelector('button'); if (btn) btn.disabled = true;
    try {
      const r = await fetch(f.action, { method: 'POST', body: new FormData(f), headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
      if (!r.ok) throw new Error('request failed');
      const d = await r.json();
      if ('cart' in d) badge('cart-badge', d.cart);
      if ('wishlist' in d) {
        badge('wl-badge', d.wishlist);
        f.querySelectorAll('.wl-heart').forEach(s => { s.setAttribute('fill', d.active ? 'currentColor' : 'none'); if (d.active) bump(s); });
        f.querySelectorAll('.wl-on').forEach(s => s.classList.toggle('hidden', !d.active));
        f.querySelectorAll('.wl-off').forEach(s => s.classList.toggle('hidden', d.active));
        const page = f.closest('[data-wishlist-page]');
        if (page && !d.active) { f.closest('article').remove(); if (!page.querySelector('article')) location.reload(); }
      }
      say(d.message);
    } catch (err) { f.submit(); return; }   // fall back to a normal form submit
    if (btn) btn.disabled = false;
  });
})();
</script>
<script>
// Scroll animations: progress bar, reveal-on-scroll, parallax, counters, back-to-top
(() => {
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];
  const bar = document.getElementById('scrollbar'), header = document.querySelector('header'), toTop = document.getElementById('to-top');
  const parallax = reduce ? [] : $$('main .bg-cover:not(.kb)');       // any banner/hero with a background image

  /* one rAF-throttled scroll handler for everything scroll-linked */
  let queued = false;
  const frame = () => {
    queued = false;
    const y = scrollY, max = document.documentElement.scrollHeight - innerHeight, vh = innerHeight;
    if (bar) bar.style.transform = 'scaleX(' + (max > 0 ? Math.min(y / max, 1) : 0) + ')';
    if (header) header.classList.toggle('is-scrolled', y > 8);
    if (toTop) toTop.classList.toggle('is-on', y > 600);
    parallax.forEach(el => {
      const r = el.getBoundingClientRect();
      if (r.bottom < 0 || r.top > vh) return;
      const p = (vh - r.top) / (vh + r.height);                         // 0 = entering, 1 = leaving
      el.style.backgroundPositionY = (30 + p * 40).toFixed(1) + '%';
    });
  };
  addEventListener('scroll', () => { if (!queued) { queued = true; requestAnimationFrame(frame); } }, { passive: true });
  addEventListener('resize', frame); frame();
  if (toTop) toTop.addEventListener('click', () => scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' }));

  /* number counters: <p data-count>12,000+</p> counts up from 0 when it scrolls into view */
  if (!reduce && 'IntersectionObserver' in window) {
    const co = new IntersectionObserver(es => es.forEach(e => {
      if (!e.isIntersecting) return; co.unobserve(e.target);
      const { el, target, fmt } = e.target._count, t0 = performance.now(), dur = 1600;
      const tick = now => { const k = Math.min((now - t0) / dur, 1); el.textContent = fmt(target * (1 - Math.pow(1 - k, 3))); if (k < 1) requestAnimationFrame(tick); };
      requestAnimationFrame(tick);
    }), { threshold: .6 });
    $$('[data-count]').forEach(el => {
      const m = el.textContent.trim().match(/^([^\d]*)([\d,]*\.?\d+)(.*)$/); if (!m) return;
      const dec = (m[2].split('.')[1] || '').length, target = parseFloat(m[2].replace(/,/g, ''));
      const fmt = n => m[1] + n.toLocaleString('en-US', { minimumFractionDigits: dec, maximumFractionDigits: dec }) + m[3];
      el._count = { el, target, fmt }; el.textContent = fmt(0); co.observe(el);
    });
  }

  /* reveal on scroll: finds headings, cards, grids, banners and timeline items automatically */
  if (reduce || !('IntersectionObserver' in window)) return;
  const above = document.querySelector('main > :first-child');          // hero / carousel: don't hide what's already on screen
  const targets = $$('main .grid > *, footer .grid > *, main h2, main ol > li, main .space-y-3 > *, main > section > .rounded-2xl, main figure');
  const io = new IntersectionObserver(es => es.forEach(e => {
    if (!(e.isIntersecting || e.boundingClientRect.top < 0)) return;     // (already scrolled past -> just show it)
    const el = e.target; io.unobserve(el); el.classList.add('is-in');
    setTimeout(() => { el.classList.remove('reveal', 'is-in'); el.removeAttribute('data-reveal'); el.style.removeProperty('--d'); },
               900 + (parseInt(el.style.getPropertyValue('--d')) || 0));  // clean up so hover effects work normally afterwards
  }), { rootMargin: '0px 0px -8% 0px' });

  targets.forEach(el => {
    if (above && above.contains(el)) return;
    if (el.parentElement.closest('.reveal')) return;                     // an ancestor is already animating
    if (el.matches('.grid > *') && el.querySelector('.grid')) return;    // layout wrapper, not content
    const sibs = [...el.parentElement.children], i = sibs.indexOf(el);
    if (el.matches('ol > li')) el.dataset.reveal = 'left';
    else if (el.matches('.rounded-2xl')) el.dataset.reveal = 'zoom';
    else if (el.matches('.grid > *') && sibs.length === 2) el.dataset.reveal = i ? 'right' : 'left';   // two-column layouts slide in from both sides
    el.style.setProperty('--d', (el.matches('.grid > *, .space-y-3 > *') ? (i % 4) * 90 : 0) + 'ms');
    el.classList.add('reveal'); io.observe(el);
  });
})();
</script>
</body>
</html>

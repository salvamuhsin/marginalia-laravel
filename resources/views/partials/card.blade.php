<article class="group bg-card rounded-lg border border-ink/10 overflow-hidden flex flex-col transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-moss/40">
  <div class="relative">
    <a href="{{ route('products.show', $p) }}" class="block overflow-hidden"><div class="transition-transform duration-500 ease-out group-hover:scale-105">@include('partials.cover', ['p' => $p])</div></a>
    <form method="post" action="{{ route('wishlist.toggle', $p) }}" class="absolute top-2 right-2" data-ajax>
      @csrf
      <button aria-label="Toggle wishlist" class="rounded-full bg-card/90 p-2 text-ember">@include('partials.icon', ['ic_name' => 'heart', 'ic_class' => 'w-5 h-5 wl-heart', 'ic_fill' => in_array($p->id, session('wishlist', []))])</button>
    </form>
  </div>
  <div class="p-4 flex flex-col flex-1">
    <h3 class="font-medium leading-snug"><a href="{{ route('products.show', $p) }}">{{ $p->title }}</a></h3>
    <p class="text-sm text-ink/60">{{ $p->author ?: $p->category->name }}</p>
    <div class="mt-auto pt-3 flex flex-col items-center gap-2 text-center sm:flex-row sm:justify-between sm:text-left">
      <span class="font-bold">{{ $p->price_formatted }}</span>
      @if($p->stock > 0)
        <form method="post" action="{{ route('cart.add', $p) }}" data-ajax>
          @csrf
          <button class="text-sm rounded bg-moss text-paper px-3 py-1.5">Add to cart</button>
        </form>
      @else<span class="text-sm text-ink/50">Sold out</span>@endif
    </div>
  </div>
</article>
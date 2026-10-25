@if($p->image)
  <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->title }}" class="w-full object-cover {{ $p->type === 'book' ? 'aspect-[2/3]' : 'aspect-square' }}">
@elseif($p->type === 'book')
  <div class="aspect-[2/3] flex flex-col justify-between p-4 text-white overflow-hidden"
       style="background: linear-gradient(90deg, rgba(0,0,0,.25) 0 6px, transparent 6px), {{ $p->cover_color }}">
    <span class="font-serif leading-tight {{ ($big ?? false) ? 'text-3xl' : 'text-lg' }}">{{ $p->title }}</span>
    <span class="text-xs opacity-80">{{ $p->author }}</span>
  </div>
@else
  <div class="aspect-square flex flex-col items-center justify-center gap-2 p-4 text-white text-center" style="background: {{ $p->cover_color }}">
    <span class="{{ ($big ?? false) ? 'text-8xl' : 'text-5xl' }}">{{ $p->category->icon ?: '📦' }}</span>
    <span class="font-serif leading-tight {{ ($big ?? false) ? 'text-2xl' : 'text-sm' }}">{{ $p->title }}</span>
  </div>
@endif

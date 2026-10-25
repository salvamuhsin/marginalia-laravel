@extends('layout')
@section('title', 'Admin')
@section('content')
<div class="max-w-7xl mx-auto px-4 mt-8 grid md:grid-cols-[200px_1fr] gap-8">
  <aside class="bg-card border border-ink/10 rounded-lg p-3 h-fit space-y-1">
    <p class="font-serif text-lg px-3 py-1">Admin</p>
    @foreach([['admin.dashboard','admin.dashboard','Dashboard'],['admin.products.index','admin.products.*','Products'],['admin.categories.index','admin.categories.*','Categories'],['admin.orders.index','admin.orders.*','Orders'],['admin.inbox','admin.inbox*','Inbox']] as [$r,$p,$l])
      <a href="{{ route($r) }}" class="block px-3 py-2 rounded {{ request()->routeIs($p) ? 'bg-moss text-paper' : 'hover:bg-ink/5' }}">{{ $l }}</a>
    @endforeach
  </aside>
  <section class="min-w-0">
    @if($errors->any())
    <div class="mb-4 rounded bg-red-500/10 border border-red-500/40 text-red-700 px-4 py-3 text-sm">Please fix the highlighted fields.</div>
    @endif
    @yield('admin')
  </section>
</div>
@endsection

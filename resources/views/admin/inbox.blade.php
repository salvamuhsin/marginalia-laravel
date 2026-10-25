@extends('admin.layout')
@section('admin')
<h1 class="font-serif text-3xl mb-4">Inbox</h1>
<h2 class="font-bold mb-2">Contact messages</h2>
<div class="space-y-3 mb-8">@forelse($messages as $m)
  <div class="bg-card border border-ink/10 rounded-lg p-4">
    <div class="flex justify-between gap-3">
      <p class="font-medium">{{ $m->subject }} <span class="font-normal text-ink/60">— {{ $m->name }} ({{ $m->email }})</span></p>
      <form method="post" action="{{ route('admin.inbox.delete', $m) }}" onsubmit="return confirm('Delete message?')">
        @csrf @method('DELETE')
        <button class="text-sm underline text-red-600">Delete</button>
      </form>
    </div>
    <p class="mt-2 whitespace-pre-line text-sm">{{ $m->message }}</p>
    <p class="mt-2 text-xs text-ink/60">{{ $m->created_at->diffForHumans() }}</p>
  </div>
  @empty
    <p class="text-ink/60">No messages yet.</p>
  @endforelse
</div>
<h2 class="font-bold mb-2">Newsletter subscribers ({{ $subscribers->count() }})</h2>
<div class="bg-card border border-ink/10 rounded-lg p-4 text-sm">
  @forelse($subscribers as $s)
    <p class="py-0.5">{{ $s->email }} <span class="text-ink/50">· {{ $s->created_at->format('d M Y') }}</span></p>
  @empty
    <p class="text-ink/60">No subscribers yet.</p>
  @endforelse
</div>
@endsection

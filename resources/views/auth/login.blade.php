@extends('layout')
@section('title', 'Log in')
@section('content')
<div class="max-w-md mx-auto px-4 mt-14">
  <form method="post" action="{{ route('login') }}" class="bg-card border border-ink/10 rounded-lg p-8 space-y-4">
    @csrf
    <h1 class="font-serif text-3xl text-center">Log in</h1>
    @if(str_contains((string) session('url.intended'), 'checkout'))
      <p class="rounded bg-ember/20 px-3 py-2 text-sm">Please log in or create an account to place your order.</p>
      @endif
    <label class="block"><span class="font-medium">Email</span>
      <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded border border-ink/25 bg-paper px-3 py-2">
      @error('email')<span class="text-sm text-red-600">{{ $message }}</span>@enderror
    </label>
    <label class="block"><span class="font-medium">Password</span>
      <input type="password" name="password" required class="mt-1 w-full rounded border border-ink/25 bg-paper px-3 py-2">
    </label>
    <label class="flex items-center gap-2 text-sm">
      <input type="checkbox" name="remember"> Remember me
    </label>
    <button class="w-full rounded bg-moss text-paper py-2.5 font-medium">Log in</button>
    <p class="text-sm">New here? <a href="{{ route('register') }}" class="underline text-moss">Create an account</a></p>
  </form>
</div>
@endsection

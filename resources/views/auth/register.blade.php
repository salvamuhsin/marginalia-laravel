@extends('layout')
@section('title', 'Create account')
@section('content')
<div class="max-w-md mx-auto px-4 mt-14">
  <form method="post" action="{{ route('register') }}" class="bg-card border border-ink/10 rounded-lg p-8 space-y-4">@csrf
    <h1 class="font-serif text-3xl text-center">Create account</h1>
    @foreach(['name' => ['Name', 'text'], 'email' => ['Email', 'email'], 'password' => ['Password (8+ characters)', 'password'], 'password_confirmation' => ['Confirm password', 'password']] as $f => [$l, $t])
      <label class="block"><span class="font-medium">{{ $l }}</span>
        <input type="{{ $t }}" name="{{ $f }}" value="{{ $t === 'password' ? '' : old($f) }}" required class="mt-1 w-full rounded border border-ink/25 bg-paper px-3 py-2">
        @error($f)<span class="text-sm text-red-600">{{ $message }}</span>@enderror
      </label>
    @endforeach
    <button class="w-full rounded bg-moss text-paper py-2.5 font-medium">Sign up</button>
    <p class="text-sm">Already registered? <a href="{{ route('login') }}" class="underline text-moss">Log in</a></p>
  </form>
</div>
@endsection

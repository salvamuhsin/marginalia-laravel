@extends('admin.layout')
@section('admin')
  <h1 class="font-serif text-3xl mb-4">Edit category</h1>
  <form method="post" action="{{ route('admin.categories.update', $category) }}" class="bg-card border border-ink/10 rounded-lg p-6 max-w-lg space-y-4">
    @csrf @method('PUT')
    <label class="block">Name<input name="name" value="{{ old('name', $category->name) }}" required class="mt-1 w-full rounded border border-ink/25 bg-paper px-3 py-2">
      @error('name')
        <span class="text-sm text-red-600">{{ $message }}</span>
      @enderror
    </label>
    <label class="block">Type
      <select name="type" class="mt-1 w-full rounded border border-ink/25 bg-paper px-3 py-2">
        @foreach(['book' => 'Book', 'accessory' => 'Accessory'] as $v => $l)
          <option value="{{ $v }}" @selected(old('type', $category->type) === $v)>{{ $l }}</option>
        @endforeach
      </select>
    </label>
    <label class="block">Icon (emoji)
      <input name="icon" value="{{ old('icon', $category->icon) }}" maxlength="8" class="mt-1 w-full rounded border border-ink/25 bg-paper px-3 py-2">
    </label>
    <div class="flex gap-3">
      <button class="rounded bg-moss text-paper px-6 py-2.5 font-medium">Save</button>
      <a href="{{ route('admin.categories.index') }}" class="rounded border border-ink/25 px-5 py-2.5">Cancel</a>
    </div>
  </form>
@endsection

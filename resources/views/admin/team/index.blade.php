@extends('admin.layout')
@section('title', 'Team')

@section('content')
<x-admin.page-header title="Team &amp; Trustees" subtitle="Trustees, veterinarians, and core volunteers shown on the website.">
    <x-slot:cta><a href="{{ route('admin.team.create') }}" class="btn btn-primary text-sm">+ Add member</a></x-slot:cta>
</x-admin.page-header>

<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
    @forelse ($members as $m)
        <div class="admin-card p-5 text-center">
            @if ($m->photo)<img src="{{ asset('storage/' . $m->photo) }}" class="w-24 h-24 rounded-full mx-auto object-cover" alt="">@else<div class="w-24 h-24 rounded-full mx-auto bg-saffron-200 flex items-center justify-center text-saffron-800 font-bold text-2xl">{{ \Illuminate\Support\Str::of($m->name)->substr(0,1) }}</div>@endif
            <div class="font-semibold mt-3">{{ $m->name }}</div>
            <div class="text-xs text-gray-500">{{ $m->role }}</div>
            <div class="text-xs text-gray-400">{{ \Illuminate\Support\Str::title($m->group) }}</div>
            <div class="flex gap-2 mt-3">
                <a href="{{ route('admin.team.edit', $m) }}" class="btn btn-secondary text-xs flex-1">Edit</a>
                <form method="POST" action="{{ route('admin.team.destroy', $m) }}" onsubmit="return confirm('Delete?')" class="flex-1">
                    @csrf @method('DELETE')<button class="btn text-xs bg-red-50 text-red-700 w-full">Delete</button>
                </form>
            </div>
        </div>
    @empty
        <p class="col-span-full text-gray-500 text-center py-12">No team members added.</p>
    @endforelse
</div>
<div class="mt-4">{{ $members->links() }}</div>
@endsection

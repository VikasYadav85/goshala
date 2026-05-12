@extends('admin.layout')
@section('title', 'Testimonials')

@section('content')
<x-admin.page-header title="Testimonials" subtitle="Donor, devotee, volunteer voices">
    <x-slot:cta><a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary text-sm">+ New testimonial</a></x-slot:cta>
</x-admin.page-header>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse ($testimonials as $t)
        <div class="admin-card p-5">
            <div class="flex items-center gap-3 mb-2">
                @if ($t->avatar)<img src="{{ asset('storage/' . $t->avatar) }}" class="w-10 h-10 rounded-full object-cover" alt="">@else<div class="w-10 h-10 rounded-full bg-saffron-200 flex items-center justify-center text-saffron-800 font-bold">{{ \Illuminate\Support\Str::of($t->name)->substr(0,1) }}</div>@endif
                <div>
                    <div class="font-semibold">{{ $t->name }}</div>
                    <div class="text-xs text-gray-500">{{ $t->role }}</div>
                </div>
            </div>
            <p class="text-sm text-gray-700 italic line-clamp-3">"{{ $t->quote }}"</p>
            <div class="flex items-center justify-between mt-3 text-xs">
                <span class="text-gray-400">{{ $t->rating }}/5</span>
                <div>
                    @if ($t->is_published)<span class="badge badge-success">Published</span>@else<span class="badge badge-neutral">Draft</span>@endif
                    @if ($t->is_featured)<span class="badge badge-info ml-1">Featured</span>@endif
                </div>
            </div>
            <div class="flex gap-2 mt-3">
                <a href="{{ route('admin.testimonials.edit', $t) }}" class="btn btn-secondary text-xs flex-1">Edit</a>
                <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}" onsubmit="return confirm('Delete?')" class="flex-1">
                    @csrf @method('DELETE')<button class="btn text-xs bg-red-50 text-red-700 w-full">Delete</button>
                </form>
            </div>
        </div>
    @empty
        <p class="col-span-full text-gray-500 text-center py-12">No testimonials yet.</p>
    @endforelse
</div>
<div class="mt-4">{{ $testimonials->links() }}</div>
@endsection

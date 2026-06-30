@extends('admin.layout')
@section('title', 'Cows')
@section('page_title', 'Cows')

@section('content')

<x-admin.page-header title="Cows" subtitle="Manage rescued cow profiles available for sponsorship.">
    <x-slot:cta>
        <a href="{{ route('admin.cows.create') }}" class="btn btn-primary text-sm">+ Add cow</a>
    </x-slot:cta>
</x-admin.page-header>

<form method="GET" class="admin-card p-4 mb-5">
    <input name="search" value="{{ request('search') }}" placeholder="Search by name…" class="form-input">
</form>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse ($cows as $cow)
        <div class="admin-card overflow-hidden">
            @if ($cow->image)
                <img src="{{ asset('storage/' . $cow->image) }}" alt="" class="w-full aspect-[4/3] object-contain object-center bg-saffron-50">
            @else
                <div class="aspect-[4/3] bg-gradient-to-br from-saffron-100 to-saffron-300 flex items-center justify-center text-4xl">🐄</div>
            @endif
            <div class="p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="font-display font-semibold">{{ $cow->name }}</div>
                        <div class="text-xs text-gray-500">{{ $cow->breed ?? '—' }} · {{ $cow->age ?? '—' }}</div>
                    </div>
                    @php $statusBadge = ['active' => 'badge-success','under_treatment' => 'badge-warning','passed_away' => 'badge-neutral'][$cow->status] ?? 'badge-neutral'; @endphp
                    <span class="badge {{ $statusBadge }}">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $cow->status)) }}</span>
                </div>
                <div class="text-sm text-gray-600 mt-2">₹{{ number_format($cow->monthly_sponsorship_amount) }}/month sponsorship</div>
                <div class="flex gap-2 mt-3">
                    <a href="{{ route('admin.cows.edit', $cow) }}" class="btn btn-secondary text-xs flex-1">Edit</a>
                    <form method="POST" action="{{ route('admin.cows.destroy', $cow) }}" onsubmit="return confirm('Delete this cow profile?')" class="flex-1">
                        @csrf @method('DELETE')
                        <button class="btn text-xs bg-red-50 text-red-700 hover:bg-red-100 w-full">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <p class="col-span-full text-gray-500 text-center py-12">No cows added yet. Click <strong>Add cow</strong> to begin.</p>
    @endforelse
</div>
<div class="mt-6">{{ $cows->links() }}</div>

@endsection

@extends('admin.layout')
@section('title', 'Volunteers')
@section('page_title', 'Volunteers')

@section('content')

<form method="GET" class="admin-card p-4 mb-5 flex flex-col sm:flex-row gap-3">
    <label for="volunteer_search" class="sr-only">Search volunteers</label>
    <input id="volunteer_search" name="search" value="{{ request('search') }}" placeholder="Search by name, email, city…" class="form-input flex-1 min-w-0">
    <label for="volunteer_status_filter" class="sr-only">Filter by volunteer status</label>
    <select id="volunteer_status_filter" name="status" class="form-select w-full sm:max-w-[180px]">
        <option value="">All statuses</option>
        @foreach (['pending','approved','active','inactive','rejected'] as $s)
            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button class="btn btn-primary text-sm w-full sm:w-auto">Filter</button>
</form>

<div class="admin-card overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full admin-table">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-5 py-3">Name</th>
                <th class="text-left px-5 py-3">Contact</th>
                <th class="text-left px-5 py-3">Areas</th>
                <th class="text-left px-5 py-3">City</th>
                <th class="text-left px-5 py-3">Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($volunteers as $v)
                <tr>
                    <td class="px-5 py-3 font-medium">{{ $v->full_name }}</td>
                    <td class="px-5 py-3 text-xs text-gray-600">{{ $v->email }}<br>{{ $v->phone }}</td>
                    <td class="px-5 py-3 text-xs">
                        @foreach ((array) $v->areas_of_interest as $a)
                            <span class="badge badge-info mr-1">{{ \Illuminate\Support\Str::title(str_replace('_',' ',$a)) }}</span>
                        @endforeach
                    </td>
                    <td class="px-5 py-3 text-sm">{{ $v->city ?: '—' }}</td>
                    <td class="px-5 py-3">
                        @php $cls = ['pending' => 'badge-warning','approved' => 'badge-info','active' => 'badge-success','inactive' => 'badge-neutral','rejected' => 'badge-danger'][$v->status] ?? 'badge-neutral'; @endphp
                        <span class="badge {{ $cls }}">{{ ucfirst($v->status) }}</span>
                    </td>
                    <td class="px-5 py-3 text-right"><a href="{{ route('admin.volunteers.show', $v) }}" class="text-saffron-700 text-sm">View →</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-500 text-sm">No volunteers yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-5 py-3 border-t border-gray-100">{{ $volunteers->links() }}</div>
</div>

@endsection

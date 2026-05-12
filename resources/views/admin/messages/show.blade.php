@extends('admin.layout')
@section('title', 'Message · ' . $message->name)

@section('content')

<a href="{{ route('admin.messages.index') }}" class="text-sm text-saffron-700 hover:text-saffron-900">← Back to inbox</a>

<div class="grid lg:grid-cols-3 gap-6 mt-4">
    <div class="lg:col-span-2 admin-card p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="font-display text-xl font-bold">{{ $message->name }}</h2>
                <div class="text-sm text-gray-500">{{ $message->email }} · {{ $message->phone ?: 'No phone' }}</div>
            </div>
            <span class="badge badge-info">{{ \Illuminate\Support\Str::title($message->message_type) }}</span>
        </div>
        @if ($message->subject)<h3 class="font-semibold text-gray-900 mb-2">{{ $message->subject }}</h3>@endif
        <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $message->message }}</p>
        <div class="text-xs text-gray-400 mt-4">{{ $message->created_at->format('d M Y, h:i A') }} · IP {{ $message->ip_address }}</div>

        @if ($message->admin_reply)
            <div class="mt-6 p-4 bg-saffron-50 rounded-xl">
                <div class="text-xs uppercase tracking-widest text-saffron-700 mb-2">Internal reply</div>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $message->admin_reply }}</p>
            </div>
        @endif
    </div>

    <div class="space-y-4">
        <form method="POST" action="{{ route('admin.messages.update', $message) }}" class="admin-card p-6">
            @csrf @method('PATCH')
            <h3 class="font-display text-lg font-bold mb-4">Update</h3>
            <label class="form-label">Status</label>
            <select name="status" class="form-select mb-4">
                @foreach (['new','read','replied','spam','closed'] as $s)
                    <option value="{{ $s }}" @selected($message->status === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <label class="form-label">Internal reply / notes</label>
            <textarea name="admin_reply" rows="5" class="form-textarea mb-4">{{ $message->admin_reply }}</textarea>
            <button class="btn btn-primary w-full text-sm">Save</button>
        </form>
        <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?')">
            @csrf @method('DELETE')
            <button class="btn w-full text-sm bg-red-50 text-red-700 hover:bg-red-100">Delete message</button>
        </form>
    </div>
</div>

@endsection

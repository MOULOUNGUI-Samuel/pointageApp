{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.master2')
@section('content2')
    <div class="container py-4">
        <h4 class="mb-3">Notifications (non lues : {{ $unreadCount }})</h4>

        <form method="POST" action="{{ route('notifications.readAll') }}" class="mb-3">
            @csrf
            <button class="btn btn-outline-secondary btn-sm">Tout marquer comme lu</button>
        </form>

        <div class="list-group">
            @forelse($notifications as $n)
                @php $data = $n->data ?? []; @endphp
                <div
                    class="list-group-item d-flex justify-content-between align-items-start {{ is_null($n->read_at) ? 'bg-light' : '' }}">
                    <div>
                        <div class="fw-semibold">{{ $data['title'] ?? 'Notification' }}</div>
                        <div class="text-muted small">{{ $data['body'] ?? '' }}</div>
                        @if (!empty($data['url']))
                            <a href="{{ $data['url'] }}" class="small">Ouvrir</a>
                        @endif
                        <div class="small text-secondary mt-1">{{ $n->created_at->diffForHumans() }}</div>
                    </div>
                    <div>
                        @if (is_null($n->read_at))
                            <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                                @csrf
                                <button class="btn btn-sm btn-link">Marquer comme lue</button>
                            </form>
                        @else
                            <span class="badge bg-secondary">Lu</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-muted">Aucune notification.</div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection

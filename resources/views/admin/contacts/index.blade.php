@extends('layouts.admin')

@section('page-title', 'Communication Hub')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <p class="text-muted small mb-0">Total messages: {{ $messages->total() }}</p>
    </div>
</div>

<div class="premium-card p-0 overflow-hidden shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4 py-3">Sender Details</th>
                    <th class="py-3">Subject</th>
                    <th class="py-3">Status</th>
                    <th class="py-3">Received At</th>
                    <th class="pe-4 py-3 text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $message)
                <tr style="border-bottom: 1px solid #f1f5f9; cursor: pointer;" data-href="{{ route('admin.contacts.show', $message->id) }}">
                    <td class="ps-4 py-4">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary-soft text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 45px; height: 45px; font-size: 14px;">
                                {{ strtoupper(substr($message->name, 0, 2)) }}
                            </div>
                            <div class="ms-3">
                                <div class="fw-bold text-dark mb-0">{{ $message->name }}</div>
                                <div class="text-muted small">{{ $message->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-500 text-dark {{ $message->status == 'unread' ? 'fw-bold' : '' }}">
                            {{ Str::limit($message->subject, 50) }}
                        </div>
                    </td>
                    <td>
                        @if($message->status == 'unread')
                            <span class="badge-premium" style="background: #fef2f2; color: #991b1b; border: 1px solid #fee2e2;">
                                <i class="fas fa-circle me-1 small"></i> Unread
                            </span>
                        @elseif($message->status == 'read')
                            <span class="badge-premium" style="background: #eff6ff; color: #1e40af; border: 1px solid #dbeafe;">
                                <i class="fas fa-check me-1 small"></i> Read
                            </span>
                        @else
                            <span class="badge-premium" style="background: #f0fdf4; color: #166534; border: 1px solid #dcfce7;">
                                <i class="fas fa-reply me-1 small"></i> Replied
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="text-dark small fw-500">{{ $message->created_at->format('M d, Y') }}</div>
                        <div class="text-muted small" style="font-size: 11px;">{{ $message->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('admin.contacts.show', $message->id) }}" class="btn btn-light btn-sm px-3 rounded-pill fw-600">
                            View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">No communication history available.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $messages->links() }}
</div>

<style>
    .bg-primary-soft { background-color: rgba(242, 101, 34, 0.1); }
    .fw-500 { font-weight: 500; }
    .fw-600 { font-weight: 600; }
</style>
@endsection


@extends('layouts.admin')

@section('page-title', 'Overview')

@section('content')
<div class="row g-4">
    <!-- Stat Widgets -->
    <div class="col-xl-4 col-md-6">
        <div class="premium-card stat-widget">
            <div class="stat-icon" style="background: #e0f2fe; color: #0369a1;">
                <i class="fas fa-newspaper"></i>
            </div>
            <h6 class="text-muted fw-500 small text-uppercase mb-1">Total Blogs</h6>
            <h2 class="fw-bold mb-0">{{ $stats['total_blogs'] }}</h2>
            <div class="mt-3 text-success small fw-600">
                <i class="fas fa-arrow-up me-1"></i> Growing
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="premium-card stat-widget">
            <div class="stat-icon" style="background: #fef3c7; color: #b45309;">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <h6 class="text-muted fw-500 small text-uppercase mb-1">Messages</h6>
            <h2 class="fw-bold mb-0">{{ $stats['total_messages'] }}</h2>
            <div class="mt-3 text-warning small fw-600">
                <i class="fas fa-circle me-1"></i> New Requests
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-12">
        <div class="premium-card stat-widget">
            <div class="stat-icon" style="background: #fdf2f2; color: #991b1b;">
                <i class="fas fa-eye"></i>
            </div>
            <h6 class="text-muted fw-500 small text-uppercase mb-1">Engagements</h6>
            <h2 class="fw-bold mb-0">{{ $stats['total_views'] }}</h2>
            <div class="mt-3 text-danger small fw-600">
                <i class="fas fa-chart-line me-1"></i> Across All Posts
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 g-4">
    <!-- Recent Activity Table -->
    <div class="col-xl-8">
        <div class="premium-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold m-0">Recent Content</h5>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-sm btn-link text-primary text-decoration-none fw-600">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-premium align-middle mb-0">
                    <thead class="text-muted small text-uppercase">
                        <tr>
                            <th>Blog Title</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Views</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_blogs as $blog)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $blog->image ? asset($blog->image) : asset('assets/images/blog/blog6.webp') }}" class="rounded-3 me-3" style="width: 45px; height: 45px; object-fit: cover;">
                                    <div>
                                        <div class="fw-600">{{ Str::limit($blog->title, 40) }}</div>
                                        <small class="text-muted">{{ $blog->category ?? 'Uncategorized' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($blog->status == 'published')
                                    <span class="badge-premium" style="background: #dcfce7; color: #166534;">Published</span>
                                @else
                                    <span class="badge-premium" style="background: #fef9c3; color: #854d0e;">Draft</span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $blog->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="fw-600 text-dark">{{ number_format($blog->views) }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Message Panel -->
    <div class="col-xl-4">
        <div class="premium-card p-4 h-100">
            <h5 class="fw-bold mb-4">Inbox Snapshot</h5>
            <div class="inbox-list">
                @forelse($recent_messages as $message)
                <div class="d-flex align-items-start mb-4 pb-4 border-bottom last-border-0">
                    <div class="bg-primary-soft text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; flex-shrink: 0;">
                        <i class="fas fa-user small"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h6 class="m-0 fw-bold small text-truncate" style="max-width: 120px;">{{ $message->name }}</h6>
                            <span class="text-muted small" style="font-size: 10px;">{{ $message->created_at->diffForHumans(null, true) }}</span>
                        </div>
                        <div class="text-muted small text-truncate">{{ $message->subject }}</div>
                    </div>
                    <a href="{{ route('admin.contacts.show', $message->id) }}" class="ms-2 text-primary small">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                @empty
                <div class="text-center py-5">
                    <img src="https://illustrations.popsy.co/gray/envelope.svg" alt="empty" style="width: 80px; opacity: 0.5;">
                    <p class="text-muted small mt-3">No new messages</p>
                </div>
                @endforelse
            </div>
            <a href="{{ route('admin.contacts.index') }}" class="btn w-100 btn-light btn-sm fw-600 mt-2">Open All Messages</a>
        </div>
    </div>
</div>

<style>
    .last-border-0:last-child { border-bottom: none !important; }
    .fw-500 { font-weight: 500; }
    .fw-600 { font-weight: 600; }
</style>
@endsection


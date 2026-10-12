@extends('layouts.admin')

@section('page-title', 'Blog Library')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/redesign/vendor/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/redesign/css/admin-tables.css') }}">
@endpush
@push('scripts')
<script src="{{ asset('assets/redesign/vendor/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/redesign/js/admin-datatables.js') }}"></script>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <p class="text-muted small mb-0">Total of {{ $blogs->count() }} articles published</p>
    </div>
    <a href="{{ route('admin.blogs.create') }}" class="btn btn-premium btn-premium-primary">
        <i class="fas fa-plus me-2"></i> Create New Article
    </a>
</div>

<div class="premium-card rd-dt-card p-0 overflow-hidden shadow-sm">
    <div class="table-responsive">
        <table id="blogsTable" class="table table-hover align-middle mb-0 rd-dt" data-rd-datatable data-rd-nosort="5" data-rd-order-col="4" data-rd-order-dir="desc" style="border-collapse: collapse;">
            <thead class="bg-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4 py-3">Article</th>
                    <th class="py-3">Category</th>
                    <th class="py-3">Status</th>
                    <th class="py-3 text-center">Views</th>
                    <th class="py-3">Created At</th>
                    <th class="pe-4 py-3 text-end">Manage</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blogs as $blog)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td class="ps-4 py-4">
                        <div class="d-flex align-items-center">
                            <div class="position-relative">
                                <img src="{{ $blog->image ? asset($blog->image) : asset('assets/images/blog/blog6.webp') }}" 
                                     class="rounded-3" style="width: 60px; height: 60px; object-fit: cover;">
                            </div>
                            <div class="ms-3">
                                <div class="fw-bold text-dark mb-1">{{ Str::limit($blog->title, 50) }}</div>
                                <div class="text-muted small">/blog/{{ $blog->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-dark fw-500">{{ $blog->category ?? 'Unassigned' }}</span>
                    </td>
                    <td>
                        @if($blog->status == 'published')
                            <span class="badge-premium" style="background: #ecfdf5; color: #065f46; border: 1px solid #d1fae5;">
                                <i class="fas fa-check-circle me-1 small"></i> Published
                            </span>
                        @else
                            <span class="badge-premium" style="background: #fffbeb; color: #92400e; border: 1px solid #fef3c7;">
                                <i class="fas fa-clock me-1 small"></i> Draft
                            </span>
                        @endif
                    </td>
                    <td class="text-center" data-order="{{ (int) $blog->views }}">
                        <span class="fw-bold">{{ number_format($blog->views) }}</span>
                    </td>
                    <td data-order="{{ $blog->created_at->timestamp }}">
                        <div class="text-dark small fw-500">{{ $blog->created_at->format('M d, Y') }}</div>
                        <div class="text-muted small" style="font-size: 11px;">{{ $blog->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="pe-4 text-end">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-circle p-0" style="width: 32px; height: 32px;" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v small"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg py-2" style="border-radius: 12px;">
                                <li>
                                    <a class="dropdown-item py-2 fw-500" href="{{ route('admin.blogs.edit', $blog->id) }}">
                                        <i class="fas fa-edit me-2 text-primary"></i> Edit Post
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 fw-500" href="{{ route('blog-details', $blog->slug) }}" target="_blank">
                                        <i class="fas fa-eye me-2 text-info"></i> View Live
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider opacity-50"></li>
                                <li>
                                    <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item py-2 fw-500 text-danger" data-confirm="Archive this article?">
                                            <i class="fas fa-trash-alt me-2"></i> Move to Trash
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted">No articles found in the library.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .fw-500 { font-weight: 500; }
    .dropdown-item:hover { background: #f8fafc; color: var(--primary) !important; }
    .dropdown-item i { width: 20px; }
</style>
@endsection


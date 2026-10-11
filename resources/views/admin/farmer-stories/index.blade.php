@extends('layouts.admin')

@section('page-title', 'Farmer Stories')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <p class="text-muted small mb-0">Total of {{ $stories->total() }} farmer stories</p>
    </div>
    <a href="{{ route('admin.farmer-stories.create') }}" class="btn btn-premium btn-premium-primary">
        <i class="fas fa-plus me-2"></i> Add Farmer Story
    </a>
</div>

<div class="premium-card p-0 overflow-hidden shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="border-collapse: collapse;">
            <thead class="bg-light text-muted small text-uppercase">
                <tr>
                    <th class="ps-4 py-3">Farmer</th>
                    <th class="py-3">Mobile</th>
                    <th class="py-3">Suggested Product</th>
                    <th class="py-3">Location</th>
                    <th class="py-3 text-center">Status</th>
                    <th class="py-3">Added On</th>
                    <th class="pe-4 py-3 text-end">Manage</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stories as $story)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td class="ps-4 py-4">
                        <div class="d-flex align-items-center">
                            @if($story->image)
                                <img src="{{ asset($story->image) }}"
                                     class="rounded-3 me-3"
                                     style="width: 55px; height: 55px; object-fit: cover;">
                            @else
                                <div class="rounded-3 me-3 d-flex align-items-center justify-content-center bg-light"
                                     style="width: 55px; height: 55px; flex-shrink: 0;">
                                    <i class="fas fa-user text-muted"></i>
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold text-dark">{{ $story->farmer_name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-dark fw-500">{{ $story->mobile_number }}</td>
                    <td class="text-dark fw-500">{{ Str::limit($story->suggested_product, 40) }}</td>
                    <td class="text-muted small">{{ $story->location ?? '—' }}</td>
                    <td class="text-center">
                        @if($story->is_active)
                            <span class="badge-premium" style="background: #ecfdf5; color: #065f46; border: 1px solid #d1fae5;">
                                <i class="fas fa-check-circle me-1 small"></i> Active
                            </span>
                        @else
                            <span class="badge-premium" style="background: #fef2f2; color: #991b1b; border: 1px solid #fecaca;">
                                <i class="fas fa-times-circle me-1 small"></i> Inactive
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="text-dark small fw-500">{{ $story->created_at->format('M d, Y') }}</div>
                        <div class="text-muted small" style="font-size: 11px;">{{ $story->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="pe-4 text-end">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-circle p-0"
                                    style="width: 32px; height: 32px;"
                                    data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v small"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg py-2" style="border-radius: 12px;">
                                <li>
                                    <a class="dropdown-item py-2 fw-500"
                                       href="{{ route('admin.farmer-stories.edit', $story->id) }}">
                                        <i class="fas fa-edit me-2 text-primary"></i> Edit Story
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider opacity-50"></li>
                                <li>
                                    <form action="{{ route('admin.farmer-stories.destroy', $story->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="dropdown-item py-2 fw-500 text-danger"
                                                data-confirm="Delete this farmer story?">
                                            <i class="fas fa-trash-alt me-2"></i> Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        No farmer stories found. <a href="{{ route('admin.farmer-stories.create') }}">Add the first one.</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $stories->links() }}
</div>

<style>
    .fw-500 { font-weight: 500; }
    .dropdown-item:hover { background: #f8fafc; color: var(--primary) !important; }
    .dropdown-item i { width: 20px; }
</style>
@endsection

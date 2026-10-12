@extends('layouts.admin')

@section('page-title', 'Add Farmer Story')

@section('content')
<form action="{{ route('admin.farmer-stories.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">

        {{-- Main Content --}}
        <div class="col-xl-8">
            <div class="premium-card p-4">

                <div class="mb-4">
                    <label class="form-label fw-bold text-uppercase small text-muted">Farmer Name</label>
                    <input type="text"
                           name="farmer_name"
                           value="{{ old('farmer_name') }}"
                           class="form-control form-control-lg border-0 bg-light p-3 @error('farmer_name') is-invalid @enderror"
                           placeholder="e.g. Jamman Patel"
                           required
                           style="border-radius: 12px; font-weight: 600;">
                    @error('farmer_name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-uppercase small text-muted">Mobile Number</label>
                    <input type="text"
                           name="mobile_number"
                           value="{{ old('mobile_number') }}"
                           class="form-control border-0 bg-light p-3 @error('mobile_number') is-invalid @enderror"
                           placeholder="e.g. 6268030793"
                           required
                           style="border-radius: 12px;">
                    @error('mobile_number')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-uppercase small text-muted">Suggested Product</label>
                    <input type="text"
                           name="suggested_product"
                           value="{{ old('suggested_product') }}"
                           class="form-control border-0 bg-light p-3 @error('suggested_product') is-invalid @enderror"
                           placeholder="e.g. Koni Tron Koni Pendi"
                           required
                           style="border-radius: 12px;">
                    @error('suggested_product')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-uppercase small text-muted">Location (village / district, optional)</label>
                    <input type="text"
                           name="location"
                           value="{{ old('location') }}"
                           class="form-control border-0 bg-light p-3 @error('location') is-invalid @enderror"
                           placeholder="e.g. Kheda, Gujarat"
                           maxlength="255"
                           style="border-radius: 12px;">
                    @error('location')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-0">
                    <label class="form-label fw-bold text-uppercase small text-muted">Testimonial (optional, max 300 characters)</label>
                    <textarea name="testimonial"
                              rows="4"
                              maxlength="300"
                              class="form-control border-0 bg-light p-3 @error('testimonial') is-invalid @enderror"
                              placeholder="The farmer's own words about the result they got"
                              style="border-radius: 12px;">{{ old('testimonial') }}</textarea>
                    @error('testimonial')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>
        {{-- Sidebar Settings --}}
        <div class="col-xl-4">

            {{-- Image Upload --}}
            <div class="premium-card p-4 mb-4">
                <h6 class="fw-bold mb-4">Farmer Photo</h6>
                <div class="mb-3 text-center p-4 border-2 border-dashed rounded-4 bg-light position-relative"
                     style="border-color: #cbd5e1;">
                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3 d-block"></i>
                    <span class="text-muted small">Click to upload photo</span>
                    <input type="file"
                           name="image"
                           accept="image/*"
                           class="form-control position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer">
                </div>
                @error('image')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
                <p class="text-muted small text-center mb-0">JPG, PNG, WEBP — Max 3MB</p>
            </div>

            {{-- Visibility --}}
            <div class="premium-card p-4 mb-4">
                <h6 class="fw-bold mb-4">Visibility</h6>
                <div class="form-check form-switch">
                    <input class="form-check-input"
                           type="checkbox"
                           name="is_active"
                           id="is_active"
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           style="width: 48px; height: 24px;">
                    <label class="form-check-label ms-2 fw-500" for="is_active">
                        Show on homepage
                    </label>
                </div>
            </div>

            {{-- Actions --}}
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-premium btn-premium-primary py-3">
                    <i class="fas fa-plus me-2"></i> Add Story
                </button>
                <a href="{{ route('admin.farmer-stories.index') }}"
                   class="btn btn-light py-3 fw-bold text-muted">Cancel</a>
            </div>

        </div>
    </div>
</form>

<style>
    .fw-500 { font-weight: 500; }
    .cursor-pointer { cursor: pointer; }
</style>
@endsection

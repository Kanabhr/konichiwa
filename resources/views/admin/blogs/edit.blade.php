@extends('layouts.admin')

@section('page-title', 'Refine Article')

@section('content')
<form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <!-- Main Content Area -->
        <div class="col-xl-8">
            <div class="premium-card p-4">
                <div class="mb-4">
                    <label class="form-label fw-bold text-uppercase small text-muted">Article Title</label>
                    <input type="text" name="title" class="form-control form-control-lg border-0 bg-light p-3 @error('title') is-invalid @enderror" value="{{ old('title', $blog->title) }}" required style="border-radius: 12px; font-weight: 600;">
                    @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold text-uppercase small text-muted">Detailed Content</label>
                    <textarea id="summernote" name="content" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $blog->content) }}</textarea>
                    @error('content') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-0">
                    <label class="form-label fw-bold text-uppercase small text-muted">Summary / Excerpt</label>
                    <textarea name="excerpt" class="form-control border-0 bg-light p-3" rows="3" style="border-radius: 12px;">{{ old('excerpt',$blog->excerpt) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Sidebar / Settings Area -->
        <div class="col-xl-4">
            <div class="premium-card p-4 mb-4">
                <h6 class="fw-bold mb-4">Publication Settings</h6>
                
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">VISIBILITY STATUS</label>
                    <select name="status" class="form-select border-0 bg-light p-3" style="border-radius: 10px; font-weight: 600;">
                        <option value="published" {{ $blog->status == 'published' ? 'selected' : '' }}>Public (Live)</option>
                        <option value="draft" {{ $blog->status == 'draft' ? 'selected' : '' }}>Private (Draft)</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">PRIMARY CATEGORY</label>
                    <input type="text" name="category" class="form-control border-0 bg-light p-3" value="{{ $blog->category }}" style="border-radius: 10px;">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">AUTHOR NAME</label>
                    <input type="text" name="author" class="form-control border-0 bg-light p-3" value="{{ $blog->author }}" placeholder="e.g. John Smith" style="border-radius: 10px;">
                    @error("author") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-uppercase small text-muted">SEO Meta Description</label>
                    <input type="text" name="meta_description" class="form-control border-0 bg-light p-3" value="{{ $blog->meta_description }}" style="border-radius: 12px;">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-uppercase small text-muted">SEO Meta Keywords</label>
                    <input type="text" name="meta_keywords" class="form-control border-0 bg-light p-3" value="{{ $blog->meta_keywords }}" style="border-radius: 12px;">
                </div>

                <div class="mb-0">
                    <label class="form-label small fw-bold text-muted">TAGS (COMMA SEPARATED)</label>
                    <input type="text" name="tags" class="form-control border-0 bg-light p-3" value="{{ $blog->tags }}" style="border-radius: 10px;">
                </div>
            </div>

            <div class="premium-card p-4 mb-4">
                <h6 class="fw-bold mb-4">Featured Image</h6>
                @if($blog->image)
                    <div class="mb-3 rounded-4 overflow-hidden border shadow-sm">
                        <img src="{{ asset($blog->image) }}" class="img-fluid" alt="preview">
                    </div>
                @endif
                <div class="mb-3 text-center p-4 border-2 border-dashed rounded-4 bg-light position-relative" style="border-color: #cbd5e1;">
                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2 d-block"></i>
                    <span class="text-muted small">Update image by clicking here</span>
                    <input type="file" name="image" class="form-control position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer">
                </div>
                <p class="text-muted small text-center mb-0">Recommended: 1200 x 800 px</p>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-premium btn-premium-primary py-3">
                    <i class="fas fa-save me-2"></i> Save Changes
                </button>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-light py-3 fw-bold text-muted">Cancel Editing</a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-airframe, .note-editor.note-frame {
        border: none !important;
        background: #f8fafc !important;
        border-radius: 12px !important;
        overflow: hidden;
    }
    .note-toolbar {
        background: #f1f5f9 !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }
    .cursor-pointer { cursor: pointer; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            placeholder: 'Continue your story...',
            tabsize: 2,
            height: 500,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endpush




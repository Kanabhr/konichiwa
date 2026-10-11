@extends('layouts.admin')

@section('page-title', 'Message Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">
        <div class="premium-card overflow-hidden">
            <div class="p-4 bg-light border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 50px; height: 50px;">
                        {{ strtoupper(substr($message->name, 0, 1)) }}
                    </div>
                    <div class="ms-3">
                        <h5 class="fw-bold mb-0">{{ $message->name }}</h5>
                        <div class="text-muted small">{{ $message->email }}</div>
                    </div>
                </div>
                <div class="text-end">
                    <div class="text-muted small">Sent {{ $message->created_at->format('M d, Y') }}</div>
                    <div class="text-muted small">{{ $message->created_at->format('h:i A') }}</div>
                </div>
            </div>
            
            <div class="p-5">
                <div class="mb-5">
                    <h6 class="text-muted small text-uppercase fw-bold mb-3" style="letter-spacing: 1px;">Subject Line</h6>
                    <h4 class="fw-bold text-dark">{{ $message->subject }}</h4>
                </div>

                <div class="mb-5">
                    <h6 class="text-muted small text-uppercase fw-bold mb-3" style="letter-spacing: 1px;">Message Content</h6>
                    <div class="bg-light p-4 rounded-4" style="line-height: 1.8; font-size: 16px; color: #1e293b;">
                        {!! nl2br(e($message->message)) !!}
                    </div>
                </div>

                @if($message->phone)
                <div class="mb-5">
                    <h6 class="text-muted small text-uppercase fw-bold mb-2" style="letter-spacing: 1px;">Contact Phone</h6>
                    <div class="fw-bold">{{ $message->phone }}</div>
                </div>
                @endif

                @if($message->gst_number)
                <div class="mb-5">
                    <h6 class="text-muted small text-uppercase fw-bold mb-2" style="letter-spacing: 1px;">GST Number</h6>
                    <div class="fw-bold">{{ $message->gst_number }}</div>
                </div>
                @endif

                <div class="pt-4 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center">
                        <span class="me-3 fw-bold small text-muted">MARK AS</span>
                        <form action="{{ route('admin.contacts.update-status', $message->id) }}" method="POST" class="d-flex gap-2">
                            @csrf
                            @method('PATCH')
                            <button name="status" value="unread" class="btn btn-sm {{ $message->status == 'unread' ? 'btn-danger' : 'btn-outline-secondary' }} rounded-pill px-3">Unread</button>
                            <button name="status" value="read" class="btn btn-sm {{ $message->status == 'read' ? 'btn-info text-white' : 'btn-outline-secondary' }} rounded-pill px-3">Read</button>
                            <button name="status" value="replied" class="btn btn-sm {{ $message->status == 'replied' ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill px-3">Replied</button>
                        </form>
                    </div>

                    <form action="{{ route('admin.contacts.destroy', $message->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm px-4 rounded-pill" data-confirm="Permanently delete this conversation?">
                            <i class="fas fa-trash-alt me-2"></i> Delete Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-link text-decoration-none text-muted fw-bold">
                <i class="fas fa-arrow-left me-2"></i> Back to Communication Hub
            </a>
        </div>
    </div>
</div>
@endsection


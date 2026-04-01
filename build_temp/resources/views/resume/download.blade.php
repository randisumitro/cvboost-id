@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <h2 class="mb-4">Download Resume</h2>
    <div class="card shadow-sm border-0 d-inline-block p-5 w-50">
        <i class="fas fa-file-pdf fa-4x text-danger mb-4"></i>
        <h4>{{ $resume->title }}</h4>
        <p class="text-muted mt-2">Your resume has been prepared in PDF format.</p>
        <a href="{{ route('resume.download', $resume->id) }}" class="btn btn-success btn-lg mt-4 px-5">
            <i class="fas fa-download me-2"></i> Download File
        </a>
        <div class="mt-4 pt-3 border-top">
            <a href="{{ route('resume.preview', $resume->id) }}" class="text-decoration-none text-muted">
                <i class="fas fa-arrow-left me-1"></i> Return to Preview
            </a>
        </div>
    </div>
</div>
@endsection

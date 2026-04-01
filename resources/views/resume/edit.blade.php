@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <div class="alert alert-info d-inline-block px-5 shadow-sm text-start" style="max-width: 600px;">
        <h4 class="alert-heading border-bottom pb-2 mb-3"><i class="fas fa-pencil-alt text-primary me-2"></i>Edit Resume</h4>
        <p>The Edit Resume builder is currently being configured. In the meantime, you can create a new resume or duplicate an existing one to make modifications.</p>
        <hr>
        <div class="d-flex justify-content-end gap-2 mt-3 mb-0">
            <a href="{{ route('resume.preview', $resume->id) }}" class="btn btn-outline-secondary">Go Back</a>
            <a href="{{ route('resume.create') }}" class="btn btn-primary">Create New</a>
        </div>
    </div>
</div>
@endsection

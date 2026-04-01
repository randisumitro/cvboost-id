@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Resumes</h2>
        <a href="{{ route('resume.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Resume
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($resumes->isEmpty())
        <div class="card text-center p-5 shadow-sm">
            <div class="card-body">
                <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                <h4 class="card-title">No Resumes Yet</h4>
                <p class="card-text text-muted">You haven't created any resumes. Start building your professional CV today!</p>
                <a href="{{ route('resume.create') }}" class="btn btn-primary mt-3">Create Your First Resume</a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($resumes as $resume)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $resume->title }}</h5>
                            <p class="text-muted small mb-2">Template: {{ $resume->template->name }}</p>
                            <p class="text-muted small mb-3">Last updated: {{ $resume->updated_at->diffForHumans() }}</p>

                            @if($resume->ats_score)
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>ATS Score</span>
                                        <span class="fw-bold">{{ $resume->ats_score }}/100</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-{{ $resume->ats_score >= 80 ? 'success' : ($resume->ats_score >= 60 ? 'warning' : 'danger') }}" 
                                             role="progressbar" style="width: {{ $resume->ats_score }}%">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex flex-wrap gap-2 mt-auto pt-3 border-top">
                                <a href="{{ route('resume.preview', $resume->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('resume.edit', $resume->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('resume.duplicate', $resume->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </form>
                                <form action="{{ route('resume.delete', $resume->id) }}" method="POST" class="d-inline ms-auto" onsubmit="return confirm('Are you sure you want to delete this resume?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

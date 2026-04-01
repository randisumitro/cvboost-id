@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">ATS Score Analysis</h4>
                    <a href="{{ route('resume.preview', $resume->id) }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Resume
                    </a>
                </div>

                <div class="card-body">
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <!-- This simulates ATS score fetching or displaying -->
                            @if($resume->ats_score)
                                <h1 class="display-1 fw-bold {{ $resume->ats_score >= 80 ? 'text-success' : ($resume->ats_score >= 60 ? 'text-warning' : 'text-danger') }} mb-0">
                                    {{ $resume->ats_score }}
                                </h1>
                                <p class="lead text-muted">Out of 100</p>

                                <div class="progress mt-4" style="height: 10px;">
                                    <div class="progress-bar bg-{{ $resume->ats_score >= 80 ? 'success' : ($resume->ats_score >= 60 ? 'warning' : 'danger') }}"
                                         role="progressbar" style="width: {{ $resume->ats_score }}%">
                                    </div>
                                </div>
                            @else
                                <h1 class="display-4 fw-bold text-muted mb-0">Analysis Pending...</h1>
                                <p class="lead mt-3">We are analyzing your resume against ATS standards.</p>
                                <button class="btn btn-primary mt-3" onclick="simulateAtsScan()">
                                    <i class="fas fa-sync-alt"></i> Run ATS Scan Now
                                </button>
                            @endif
                        </div>

                        <div class="mt-5 text-start">
                            <h5 class="fw-bold mb-3 border-bottom pb-2">Feedback & Recommendations</h5>
                            @if($resume->ats_feedback)
                                @if(is_array($resume->ats_feedback))
                                    <ul class="list-group list-group-flush">
                                        @foreach($resume->ats_feedback as $feedback)
                                            <li class="list-group-item d-flex align-items-center">
                                                <i class="fas fa-info-circle text-primary me-3"></i>
                                                {{ $feedback }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">{{ $resume->ats_feedback }}</p>
                                @endif
                            @else
                                <ul class="list-group list-group-flush text-muted">
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-3"></i>
                                        Format uses ATS-friendly headings natively
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-check-circle text-success me-3"></i>
                                        Font types are generally accepted
                                    </li>
                                    <li class="list-group-item d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle text-warning me-3"></i>
                                        Ensure keywords from job description are present
                                    </li>
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function simulateAtsScan() {
    alert("In a real application, this would trigger an AI analysis of your resume. Generating a demo score.");
    window.location.reload();
}
</script>
@endsection

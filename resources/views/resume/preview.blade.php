@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Resume Preview</h4>
                    <div>
                        <a href="{{ route('resume.edit', $resume->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('resume.download', $resume->id) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Resume Preview -->
                    <div class="resume-preview-container" style="background: white; padding: 40px; border: 1px solid #ddd; min-height: 800px;">
                        <div class="resume-content" style="--primary-color: {{ $resume->primary_color }}; font-family: {{ $resume->font_family }}, Arial, sans-serif;">
                            @if(view()->exists('resume.templates.' . $resume->template->slug))
                                @include('resume.templates.' . $resume->template->slug)
                            @else
                                @include('resume.templates.modern-professional')
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <div>
                            <a href="{{ route('resume.create') }}" class="btn btn-outline-primary">
                                <i class="fas fa-plus"></i> Create New Resume
                            </a>
                            @if(auth()->check())
                                <a href="{{ route('resume.index') }}" class="btn btn-outline-secondary ms-2">
                                    <i class="fas fa-list"></i> My Resumes
                                </a>
                            @endif
                        </div>

                        <div>
                            <a href="{{ route('resume.ats-score', $resume->id) }}" class="btn btn-info">
                                <i class="fas fa-chart-line"></i> Check ATS Score
                            </a>
                            <button class="btn btn-success ms-2" onclick="downloadResume()">
                                <i class="fas fa-download"></i> Download PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Resume Info -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Resume Information</h6>
                </div>
                <div class="card-body">
                    <p><strong>Title:</strong> {{ $resume->title }}</p>
                    <p><strong>Template:</strong> {{ $resume->template->name }}</p>
                    <p><strong>Created:</strong> {{ $resume->created_at->format('M d, Y') }}</p>
                    <p><strong>Downloads:</strong> {{ $resume->download_count }}</p>

                    @if($resume->ats_score)
                        <div class="mt-3">
                            <p><strong>ATS Score:</strong></p>
                            <div class="progress">
                                <div class="progress-bar bg-{{ $resume->ats_score >= 80 ? 'success' : ($resume->ats_score >= 60 ? 'warning' : 'danger') }}"
                                     role="progressbar" style="width: {{ $resume->ats_score }}%">
                                    {{ $resume->ats_score }}/100
                                </div>
                            </div>
                            <a href="{{ route('resume.ats-score', $resume->id) }}" class="btn btn-sm btn-outline-info mt-2">
                                View Details
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(auth()->check())
                            <form action="{{ route('resume.duplicate', $resume->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-copy"></i> Duplicate Resume
                                </button>
                            </form>
                        @endif

                        <button class="btn btn-outline-secondary" onclick="shareResume()">
                            <i class="fas fa-share"></i> Share Resume
                        </button>

                        <button class="btn btn-outline-info" onclick="printResume()">
                            <i class="fas fa-print"></i> Print Resume
                        </button>

                        <a href="{{ route('resume.html', $resume->id) }}" class="btn btn-outline-dark">
                            <i class="fas fa-code"></i> Export HTML
                        </a>
                    </div>
                </div>
            </div>

            <!-- Ad Space -->
            <div class="card">
                <div class="card-body text-center">
                    <small class="text-muted">Advertisement</small>
                    <div class="mt-2" style="height: 250px; background: #f8f9fa; border: 1px solid #dee2e6; display: flex; align-items: center; justify-content: center;">
                        <span class="text-muted">Ad Space (300x250)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.resume-preview-container {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

.resume-content {
    line-height: 1.6;
    color: #333;
}

@media print {
    .card, .btn, .d-flex.justify-content-between.mt-4 {
        display: none !important;
    }

    .resume-preview-container {
        box-shadow: none;
        border: none;
        padding: 0;
    }

    body {
        background: white;
    }
}
</style>

<style>
    {!! $resume->template->css_content !!}
</style>

@endsection

@push('scripts')
<script>
function downloadResume() {
    const resumeId = {{ $resume->id }};
    const user = @json(auth()->user());

    // Show ad for free users
    if (!user || !user.is_premium) {
        // Show interstitial ad (simulated)
        if (confirm('Download will start after viewing this ad. Click OK to continue.')) {
            // In production, show actual ad here
            setTimeout(() => {
                performDownload(resumeId);
            }, 2000);
        }
    } else {
        performDownload(resumeId);
    }
}

function performDownload(resumeId) {
    fetch(`/api/resumes/${resumeId}/generate-pdf`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.download_url) {
            // Create download link
            const link = document.createElement('a');
            link.href = data.download_url;
            link.download = 'resume.pdf';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Update download count
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error generating PDF. Please try again.');
    });
}

function shareResume() {
    const url = window.location.href;

    if (navigator.share) {
        navigator.share({
            title: 'My Resume',
            text: 'Check out my professional resume',
            url: url
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(url).then(() => {
            alert('Resume link copied to clipboard!');
        });
    }
}

function printResume() {
    window.print();
}
</script>
@endpush

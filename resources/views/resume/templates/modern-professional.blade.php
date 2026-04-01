<style>
    {!! $resume->template->css_content ?? '' !!}
    
    /* Ensure page breaks for DomPDF */
    .experience-item, .education-item { page-break-inside: avoid; }
</style>
<div class="resume-container">
    <header class="header">
        <div class="name-section">
            <h1>{{ $resume->full_name }}</h1>
            <p class="title">Professional Summary</p>
        </div>
        <div class="contact-section">
            <div class="contact-item">
                <span class="icon">📧</span>
                <span>{{ $resume->email }}</span>
            </div>
            @if($resume->phone)
            <div class="contact-item">
                <span class="icon">📱</span>
                <span>{{ $resume->phone }}</span>
            </div>
            @endif
            @if($resume->address)
            <div class="contact-item">
                <span class="icon">📍</span>
                <span>{{ $resume->address }}</span>
            </div>
            @endif
            @if($resume->linkedin)
            <div class="contact-item" id="linkedin-item">
                <span class="icon">💼</span>
                <span>{{ $resume->linkedin }}</span>
            </div>
            @endif
        </div>
    </header>

    @if($resume->summary)
    <div class="summary-section" id="summary-section">
        <h2>Professional Summary</h2>
        <p>{{ $resume->summary }}</p>
    </div>
    @endif

    @if(is_array($resume->experiences) && count($resume->experiences) > 0)
    <section class="experience-section">
        <h2>Work Experience</h2>
        <div id="experiences-container">
            @foreach($resume->experiences as $exp)
                <div class="experience-item mb-4">
                    <div class="experience-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">{{ $exp['position'] ?? '' }}</h5>
                        <div class="experience-meta text-end">
                            <span class="company fw-bold text-primary">{{ $exp['company'] ?? '' }}</span><br>
                            <span class="date text-muted small">{{ $exp['start_date'] ?? '' }} - {{ $exp['end_date'] ?? '' }}</span>
                        </div>
                    </div>
                    @if(!empty($exp['description']))
                        <p class="experience-description mt-2">{{ $exp['description'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
    @endif

    @if(is_array($resume->educations) && count($resume->educations) > 0)
    <section class="education-section">
        <h2>Education</h2>
        <div id="educations-container">
            @foreach($resume->educations as $edu)
                <div class="education-item mb-3">
                    <h5 class="mb-0 fw-bold">{{ $edu['degree'] ?? '' }}</h5>
                    <div class="education-meta d-flex justify-content-between">
                        <span class="institution text-primary">{{ $edu['institution'] ?? '' }}</span>
                        <span class="year text-muted">{{ $edu['graduation_year'] ?? '' }}</span>
                    </div>
                    @if(!empty($edu['gpa']))
                        <p class="gpa small mt-1">GPA: {{ $edu['gpa'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
    @endif

    @if(is_array($resume->skills) && count($resume->skills) > 0)
    <section class="skills-section">
        <h2>Skills</h2>
        <div class="skills-grid" id="skills-container">
            @foreach($resume->skills as $skill)
                <span class="skill-tag">{{ $skill }}</span>
            @endforeach
        </div>
    </section>
    @endif
</div>

@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Form Section -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Create Your Resume</h4>
                    
                    <!-- Progress Bar -->
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ ($currentStep / 6) * 100 }}%; background-color: var(--primary-color, #3490dc);">
                        </div>
                    </div>
                    
                    <!-- Step Indicators -->
                    <div class="d-flex justify-content-between mt-3">
                        @for($i = 1; $i <= 6; $i++)
                            <div class="step-indicator {{ $i <= $currentStep ? 'active' : '' }}">
                                <div class="step-circle">{{ $i }}</div>
                                <div class="step-title">
                                    @switch($i)
                                        @case(1)
                                            Personal Info
                                            @break
                                        @case(2)
                                            Summary
                                            @break
                                        @case(3)
                                            Experience
                                            @break
                                        @case(4)
                                            Education
                                            @break
                                        @case(5)
                                            Skills
                                            @break
                                        @case(6)
                                            Template
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
                
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('resume.step.store') }}" method="POST" id="resumeForm">
                        @csrf
                        <input type="hidden" name="step" value="{{ $currentStep }}">
                        
                        <!-- Step 1: Personal Information -->
                        @if($currentStep == 1)
                            <h5>Personal Information</h5>
                            <p class="text-muted">Let's start with your basic contact information.</p>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="{{ $resumeData['personal_data']['name'] ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="{{ $resumeData['personal_data']['email'] ?? '' }}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="{{ $resumeData['personal_data']['phone'] ?? '' }}"
                                               placeholder="+62 812-3456-7890">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" 
                                               value="{{ $resumeData['personal_data']['address'] ?? '' }}"
                                               placeholder="Jakarta, Indonesia">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="linkedin" class="form-label">LinkedIn URL</label>
                                        <input type="url" class="form-control" id="linkedin" name="linkedin" 
                                               value="{{ $resumeData['personal_data']['linkedin'] ?? '' }}"
                                               placeholder="https://linkedin.com/in/yourprofile">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="portfolio" class="form-label">Portfolio URL</label>
                                        <input type="url" class="form-control" id="portfolio" name="portfolio" 
                                               value="{{ $resumeData['personal_data']['portfolio'] ?? '' }}"
                                               placeholder="https://yourportfolio.com">
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Step 2: Professional Summary -->
                        @if($currentStep == 2)
                            <h5>Professional Summary</h5>
                            <p class="text-muted">Write a brief summary about your experience and skills (max 500 characters).</p>
                            
                            <div class="mb-3">
                                <label for="summary" class="form-label">Professional Summary</label>
                                <textarea class="form-control" id="summary" name="summary" rows="5" maxlength="500"
                                          placeholder="Experienced Software Engineer with 5+ years of expertise...">{{ $resumeData['personal_data']['summary'] ?? '' }}</textarea>
                                <div class="form-text">
                                    <span id="charCount">{{ strlen($resumeData['personal_data']['summary'] ?? '') }}</span>/500 characters
                                </div>
                            </div>
                        @endif
                        
                        <!-- Step 3: Work Experience -->
                        @if($currentStep == 3)
                            <h5>Work Experience</h5>
                            <p class="text-muted">Add your work experience. You can add multiple positions.</p>
                            
                            <div id="experiencesContainer">
                                @if(isset($resumeData['experiences']) && count($resumeData['experiences']) > 0)
                                    @foreach($resumeData['experiences'] as $index => $exp)
                                        <div class="experience-item mb-4 p-3 border rounded" data-index="{{ $index }}">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0">Experience {{ $index + 1 }}</h6>
                                                @if($index > 0)
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-experience">Remove</button>
                                                @endif
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Position *</label>
                                                        <input type="text" class="form-control" name="experiences[{{ $index }}][position]" 
                                                               value="{{ $exp['position'] ?? '' }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Company *</label>
                                                        <input type="text" class="form-control" name="experiences[{{ $index }}][company]" 
                                                               value="{{ $exp['company'] ?? '' }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Location</label>
                                                        <input type="text" class="form-control" name="experiences[{{ $index }}][location]" 
                                                               value="{{ $exp['location'] ?? '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Start Date *</label>
                                                        <input type="text" class="form-control" name="experiences[{{ $index }}][start_date]" 
                                                               value="{{ $exp['start_date'] ?? '' }}" placeholder="MM/YYYY" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">End Date</label>
                                                        <input type="text" class="form-control" name="experiences[{{ $index }}][end_date]" 
                                                               value="{{ $exp['end_date'] ?? '' }}" placeholder="MM/YYYY or Present">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control" name="experiences[{{ $index }}][description]" rows="3"
                                                          placeholder="Describe your responsibilities and achievements...">{{ $exp['description'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="experience-item mb-4 p-3 border rounded" data-index="0">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">Experience 1</h6>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Position *</label>
                                                    <input type="text" class="form-control" name="experiences[0][position]" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Company *</label>
                                                    <input type="text" class="form-control" name="experiences[0][company]" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Location</label>
                                                    <input type="text" class="form-control" name="experiences[0][location]">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Start Date *</label>
                                                    <input type="text" class="form-control" name="experiences[0][start_date]" 
                                                           placeholder="MM/YYYY" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">End Date</label>
                                                    <input type="text" class="form-control" name="experiences[0][end_date]" 
                                                           placeholder="MM/YYYY or Present">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="experiences[0][description]" rows="3"
                                                      placeholder="Describe your responsibilities and achievements..."></textarea>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <button type="button" class="btn btn-outline-primary" id="addExperience">
                                <i class="fas fa-plus"></i> Add Another Experience
                            </button>
                        @endif
                        
                        <!-- Step 4: Education -->
                        @if($currentStep == 4)
                            <h5>Education</h5>
                            <p class="text-muted">Add your educational background.</p>
                            
                            <div id="educationsContainer">
                                @if(isset($resumeData['educations']) && count($resumeData['educations']) > 0)
                                    @foreach($resumeData['educations'] as $index => $edu)
                                        <div class="education-item mb-4 p-3 border rounded" data-index="{{ $index }}">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0">Education {{ $index + 1 }}</h6>
                                                @if($index > 0)
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-education">Remove</button>
                                                @endif
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Degree/Jurusan *</label>
                                                        <input type="text" class="form-control" name="educations[{{ $index }}][degree]" 
                                                               value="{{ $edu['degree'] ?? '' }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Institution *</label>
                                                        <input type="text" class="form-control" name="educations[{{ $index }}][institution]" 
                                                               value="{{ $edu['institution'] ?? '' }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Graduation Year *</label>
                                                        <input type="text" class="form-control" name="educations[{{ $index }}][graduation_year]" 
                                                               value="{{ $edu['graduation_year'] ?? '' }}" placeholder="2020" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">GPA (Optional)</label>
                                                        <input type="text" class="form-control" name="educations[{{ $index }}][gpa]" 
                                                               value="{{ $edu['gpa'] ?? '' }}" placeholder="3.8">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="education-item mb-4 p-3 border rounded" data-index="0">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">Education 1</h6>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Degree/Jurusan *</label>
                                                    <input type="text" class="form-control" name="educations[0][degree]" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Institution *</label>
                                                    <input type="text" class="form-control" name="educations[0][institution]" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Graduation Year *</label>
                                                    <input type="text" class="form-control" name="educations[0][graduation_year]" 
                                                           placeholder="2020" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">GPA (Optional)</label>
                                                    <input type="text" class="form-control" name="educations[0][gpa]" placeholder="3.8">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <button type="button" class="btn btn-outline-primary" id="addEducation">
                                <i class="fas fa-plus"></i> Add Another Education
                            </button>
                        @endif
                        
                        <!-- Step 5: Skills -->
                        @if($currentStep == 5)
                            <h5>Skills</h5>
                            <p class="text-muted">Add your skills (minimum 3, maximum 10).</p>
                            
                            <div class="mb-3">
                                <label for="skillInput" class="form-label">Add Skills</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="skillInput" 
                                           placeholder="Type a skill and press Enter">
                                    <button class="btn btn-outline-secondary" type="button" id="addSkillBtn">Add</button>
                                </div>
                                <div class="form-text">Press Enter or click Add to add a skill</div>
                            </div>
                            
                            <div id="skillsContainer" class="mb-3">
                                @if(isset($resumeData['skills']) && count($resumeData['skills']) > 0)
                                    @foreach($resumeData['skills'] as $skill)
                                        <span class="skill-tag badge bg-primary me-2 mb-2">
                                            {{ $skill }}
                                            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeSkill(this)"></button>
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                            
                            <!-- Hidden input to store skills array -->
                            <div id="skillsHiddenContainer"></div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Add relevant skills like programming languages, tools, or soft skills.
                            </div>
                        @endif
                        
                        <!-- Step 6: Template Selection -->
                        @if($currentStep == 6)
                            <h5>Choose Template & Style</h5>
                            <p class="text-muted">Select a template and customize the appearance.</p>
                            
                            <div class="mb-4">
                                <label class="form-label">Select Template *</label>
                                <div class="row">
                                    @foreach($templates as $template)
                                        <div class="col-md-6 mb-3">
                                            <div class="template-card {{ ($resumeData['template_id'] ?? '') == $template->id ? 'selected' : '' }}"
                                                 data-template-id="{{ $template->id }}">
                                                <input type="radio" name="template_id" value="{{ $template->id }}" 
                                                       {{ ($resumeData['template_id'] ?? '') == $template->id ? 'checked' : '' }}
                                                       class="template-radio" required>
                                                
                                                <div class="template-preview">
                                                    <img src="{{ asset($template->thumbnail) }}" alt="{{ $template->name }}" 
                                                         class="img-fluid rounded border">
                                                    
                                                    @if($template->is_premium)
                                                        <span class="premium-badge">PRO</span>
                                                    @endif
                                                </div>
                                                
                                                <div class="template-info">
                                                    <h6 class="mb-1">{{ $template->name }}</h6>
                                                    <small class="text-muted">{{ $template->is_premium ? 'Premium' : 'Free' }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="primary_color" class="form-label">Primary Color *</label>
                                        <div class="input-group">
                                            <input type="color" class="form-control form-control-color" id="primary_color" 
                                                   name="primary_color" value="{{ $resumeData['primary_color'] ?? '#3490dc' }}" required>
                                            <input type="text" class="form-control" value="{{ $resumeData['primary_color'] ?? '#3490dc' }}" 
                                                   readonly id="colorHex">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="font_family" class="form-label">Font Family *</label>
                                        <select class="form-select" id="font_family" name="font_family" required>
                                            <option value="Poppins" {{ ($resumeData['font_family'] ?? 'Poppins') == 'Poppins' ? 'selected' : '' }}>Poppins</option>
                                            <option value="Arial" {{ ($resumeData['font_family'] ?? '') == 'Arial' ? 'selected' : '' }}>Arial</option>
                                            <option value="Helvetica" {{ ($resumeData['font_family'] ?? '') == 'Helvetica' ? 'selected' : '' }}>Helvetica</option>
                                            <option value="Times New Roman" {{ ($resumeData['font_family'] ?? '') == 'Times New Roman' ? 'selected' : '' }}>Times New Roman</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <div>
                                @if($currentStep > 1)
                                    <a href="{{ route('resume.previous') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left"></i> Previous
                                    </a>
                                @endif
                                
                                <a href="{{ route('resume.reset') }}" class="btn btn-outline-danger ms-2">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                            
                            <div>
                                @if($currentStep < 6)
                                    <button type="submit" class="btn btn-primary">
                                        Next <i class="fas fa-arrow-right"></i>
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check"></i> Create Resume
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Live Preview Section -->
        <div class="col-lg-6">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h5 class="mb-0">Live Preview</h5>
                </div>
                <div class="card-body">
                    <div id="livePreview" class="preview-container">
                        <!-- Preview will be loaded here via JavaScript -->
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-eye fa-3x mb-3"></i>
                            <p>Start filling the form to see live preview</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.step-indicator {
    text-align: center;
    flex: 1;
}

.step-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 5px;
    font-weight: bold;
    font-size: 14px;
}

.step-indicator.active .step-circle {
    background: var(--primary-color, #3490dc);
    color: white;
}

.step-title {
    font-size: 12px;
    color: #6c757d;
}

.step-indicator.active .step-title {
    color: var(--primary-color, #3490dc);
    font-weight: 600;
}

.template-card {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.template-card:hover {
    border-color: var(--primary-color, #3490dc);
}

.template-card.selected {
    border-color: var(--primary-color, #3490dc);
    background: rgba(52, 144, 220, 0.1);
}

.template-radio {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 1;
}

.template-preview {
    position: relative;
    margin-bottom: 10px;
}

.premium-badge {
    position: absolute;
    top: 5px;
    left: 5px;
    background: gold;
    color: #333;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: bold;
}

.skill-tag {
    position: relative;
    padding-right: 25px !important;
}

.skill-tag .btn-close {
    position: absolute;
    top: 50%;
    right: 8px;
    transform: translateY(-50%);
    font-size: 10px;
}

.preview-container {
    min-height: 400px;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

@media (max-width: 992px) {
    .step-title {
        font-size: 10px;
    }
    
    .step-circle {
        width: 25px;
        height: 25px;
        font-size: 12px;
    }
}
</style>
@endsection

@push('scripts')
<script>
@if($currentStep == 2)
// Character counter for summary
const summaryElem = document.getElementById('summary');
if (summaryElem) {
    summaryElem.addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('charCount').textContent = count;
    });
}
@endif

@if($currentStep == 3)
// Dynamically add experience fields
const addExperienceBtn = document.getElementById('addExperience');
let experienceIndex = {{ count($resumeData['experiences'] ?? []) ?: 1 }};

if (addExperienceBtn) addExperienceBtn.addEventListener('click', function() {
    const container = document.getElementById('experiencesContainer');
    if (!container) return;
    const newExperience = document.createElement('div');
    newExperience.className = 'experience-item mb-4 p-3 border rounded';
    newExperience.dataset.index = experienceIndex;
    
    newExperience.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">Experience ${experienceIndex + 1}</h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-experience">Remove</button>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Position *</label>
                    <input type="text" class="form-control" name="experiences[${experienceIndex}][position]" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Company *</label>
                    <input type="text" class="form-control" name="experiences[${experienceIndex}][company]" required>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <input type="text" class="form-control" name="experiences[${experienceIndex}][location]">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Start Date *</label>
                    <input type="text" class="form-control" name="experiences[${experienceIndex}][start_date]" 
                           placeholder="MM/YYYY" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">End Date</label>
                    <input type="text" class="form-control" name="experiences[${experienceIndex}][end_date]" 
                           placeholder="MM/YYYY or Present">
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="experiences[${experienceIndex}][description]" rows="3"
                      placeholder="Describe your responsibilities and achievements..."></textarea>
        </div>
    `;
    
    container.appendChild(newExperience);
    experienceIndex++;
});

// Remove experience
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-experience')) {
        e.target.closest('.experience-item').remove();
    }
});
@endif

@if($currentStep == 4)
// Dynamically add education fields
const addEducationBtn = document.getElementById('addEducation');
let educationIndex = {{ count($resumeData['educations'] ?? []) ?: 1 }};

if (addEducationBtn) addEducationBtn.addEventListener('click', function() {
    const container = document.getElementById('educationsContainer');
    if (!container) return;
    const newEducation = document.createElement('div');
    newEducation.className = 'education-item mb-4 p-3 border rounded';
    newEducation.dataset.index = educationIndex;
    
    newEducation.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0">Education ${educationIndex + 1}</h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-education">Remove</button>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Degree/Jurusan *</label>
                    <input type="text" class="form-control" name="educations[${educationIndex}][degree]" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Institution *</label>
                    <input type="text" class="form-control" name="educations[${educationIndex}][institution]" required>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Graduation Year *</label>
                    <input type="text" class="form-control" name="educations[${educationIndex}][graduation_year]" 
                           placeholder="2020" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">GPA (Optional)</label>
                    <input type="text" class="form-control" name="educations[${educationIndex}][gpa]" placeholder="3.8">
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(newEducation);
    educationIndex++;
});

// Remove education
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-education')) {
        e.target.closest('.education-item').remove();
    }
});
@endif

@if($currentStep == 5)
// Skills management
let skills = @json($resumeData['skills'] ?? []);

function addSkill(skill) {
    if (skills.length >= 10) {
        alert('Maximum 10 skills allowed');
        return;
    }
    
    if (!skills.includes(skill)) {
        skills.push(skill);
        updateSkillsDisplay();
    }
}

function removeSkill(button) {
    const skill = button.parentElement.textContent.trim();
    const index = skills.indexOf(skill);
    if (index > -1) {
        skills.splice(index, 1);
        updateSkillsDisplay();
    }
}

function updateSkillsDisplay() {
    const container = document.getElementById('skillsContainer');
    const hiddenContainer = document.getElementById('skillsHiddenContainer');
    
    if (!container || !hiddenContainer) return;

    container.innerHTML = skills.map(skill => 
        `<span class="skill-tag badge bg-primary me-2 mb-2">
            ${skill}
            <button type="button" class="btn-close btn-close-white ms-1" onclick="removeSkill(this)"></button>
        </span>`
    ).join('');
    
    hiddenContainer.innerHTML = skills.map((skill, index) => 
        `<input type="hidden" name="skills[${index}]" value="${skill}">`
    ).join('');
}

// Skill input handling
const skillInput = document.getElementById('skillInput');
const addSkillBtn = document.getElementById('addSkillBtn');

function addSkillFromInput() {
    if(!skillInput) return;
    const skill = skillInput.value.trim();
    if (skill && skills.length < 10) {
        addSkill(skill);
        skillInput.value = '';
        skillInput.focus();
    }
}

if (skillInput) skillInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addSkillFromInput();
    }
});

if (addSkillBtn) addSkillBtn.addEventListener('click', addSkillFromInput);

// Initial display of existing skills
updateSkillsDisplay();
@endif

@if($currentStep == 6)
// Color picker sync
const primaryColor = document.getElementById('primary_color');
const colorHex = document.getElementById('colorHex');

if (primaryColor) primaryColor.addEventListener('input', function() {
    if(colorHex) colorHex.value = this.value;
});

// Template selection
document.querySelectorAll('.template-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.template-card').forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
        const radio = this.querySelector('.template-radio');
        if(radio) radio.checked = true;
    });
});
@endif

// Live preview (simplified version)
function updateLivePreview() {
    // This would normally make an API call to get live preview
    // For now, just show a placeholder
    const currentStep = {{ $currentStep }};
    const previewContainer = document.getElementById('livePreview');
    
    if (currentStep >= 1) {
        previewContainer.innerHTML = `
            <div class="text-center text-muted py-5">
                <i class="fas fa-spinner fa-spin fa-3x mb-3"></i>
                <p>Generating preview...</p>
            </div>
        `;
        
        // Simulate preview generation
        setTimeout(() => {
            previewContainer.innerHTML = `
                <div class="p-3">
                    <div class="text-center mb-3">
                        <h4 class="text-primary">${document.getElementById('name')?.value || 'Your Name'}</h4>
                        <p class="text-muted">${document.getElementById('email')?.value || 'email@example.com'}</p>
                    </div>
                    <div class="border-top pt-3">
                        <p class="small text-muted">Live preview will be updated as you type...</p>
                    </div>
                </div>
            `;
        }, 500);
    }
}

// Initialize skills display
updateSkillsDisplay();

// Auto-save to session
setInterval(() => {
    const formData = new FormData(document.getElementById('resumeForm'));
    // This would normally save to session via AJAX
}, 30000); // Auto-save every 30 seconds
</script>
@endpush

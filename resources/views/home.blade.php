@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Create Professional Resumes That Pass ATS</h1>
                <p class="lead mb-4">Build stunning resumes in minutes with our AI-powered ATS checker. Get hired faster with professionally designed templates optimized for Applicant Tracking Systems.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('resume.create') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-plus"></i> Create Resume Now
                    </a>
                    <a href="{{ route('templates.gallery') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-eye"></i> View Templates
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="row mt-5">
                    <div class="col-4">
                        <div class="text-center">
                            <h3 class="fw-bold">10K+</h3>
                            <p>Resumes Created</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <h3 class="fw-bold">95%</h3>
                            <p>ATS Pass Rate</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <h3 class="fw-bold">500+</h3>
                            <p>Hired Candidates</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image text-center">
                    <img src="{{ asset('assets/hero-resume.jpg') }}" alt="Resume Builder" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Why Choose CVBoost.id?</h2>
            <p class="lead text-muted">Everything you need to create a professional resume that gets you hired</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-robot fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">ATS Checker</h5>
                        <p class="card-text">Our AI-powered ATS checker analyzes your resume and provides actionable suggestions to improve your score.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-palette fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Professional Templates</h5>
                        <p class="card-text">Choose from 10+ professionally designed templates optimized for different industries and career levels.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-bolt fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Real-time Preview</h5>
                        <p class="card-text">See changes instantly as you build your resume with our live preview feature.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-file-pdf fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">PDF Export</h5>
                        <p class="card-text">Download your resume as a high-quality PDF file, perfect for online applications and printing.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-mobile-alt fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Mobile Friendly</h5>
                        <p class="card-text">Create and edit your resume on any device. Our platform works seamlessly on desktop, tablet, and mobile.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-lock fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Secure & Private</h5>
                        <p class="card-text">Your data is secure with us. We use industry-standard encryption to protect your personal information.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Templates Preview Section -->
<section class="templates-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">Professional Templates</h2>
            <p class="lead text-muted">Choose from our collection of ATS-friendly templates</p>
        </div>
        
        <div class="row g-4">
            @foreach($featuredTemplates as $template)
                <div class="col-md-6 col-lg-4">
                    <div class="card template-card h-100 shadow-sm">
                        <div class="position-relative">
                            <img src="{{ asset($template->thumbnail) }}" alt="{{ $template->name }}" class="card-img-top template-thumbnail">
                            @if($template->is_premium)
                                <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">PRO</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $template->name }}</h5>
                            <p class="card-text text-muted">{{ $template->is_premium ? 'Premium template with advanced features' : 'Clean and professional design' }}</p>
                            <a href="{{ route('resume.create') }}" class="btn btn-primary">
                                Use This Template
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('templates.gallery') }}" class="btn btn-outline-primary btn-lg">
                View All Templates <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="how-it-works py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">How It Works</h2>
            <p class="lead text-muted">Create your professional resume in 4 simple steps</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="text-center">
                    <div class="step-circle mb-3">
                        <span class="step-number">1</span>
                    </div>
                    <h5>Fill Your Information</h5>
                    <p class="text-muted">Enter your personal details, work experience, education, and skills through our easy-to-use form.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="text-center">
                    <div class="step-circle mb-3">
                        <span class="step-number">2</span>
                    </div>
                    <h5>Choose Template</h5>
                    <p class="text-muted">Select from our collection of professional templates and customize colors and fonts.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="text-center">
                    <div class="step-circle mb-3">
                        <span class="step-number">3</span>
                    </div>
                    <h5>Check ATS Score</h5>
                    <p class="text-muted">Run your resume through our ATS checker to ensure it passes automated screening.</p>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="text-center">
                    <div class="step-circle mb-3">
                        <span class="step-number">4</span>
                    </div>
                    <h5>Download & Apply</h5>
                    <p class="text-muted">Download your resume as PDF and start applying to jobs with confidence.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold">What Our Users Say</h2>
            <p class="lead text-muted">Join thousands of professionals who have landed their dream jobs</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="card-text">"CVBoost.id helped me create a professional resume that passed all ATS systems. I got 3 interview calls within a week!"</p>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/user1.jpg') }}" alt="User" class="rounded-circle me-3" width="50" style="object-fit: cover; height: 50px;">
                            <div>
                                <h6 class="mb-0">Sarah Johnson</h6>
                                <small class="text-muted">Marketing Manager</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="card-text">"The ATS checker feature is amazing! It helped me optimize my resume and I landed my dream job at a tech company."</p>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/user2.jpg') }}" alt="User" class="rounded-circle me-3" width="50" style="object-fit: cover; height: 50px;">
                            <div>
                                <h6 class="mb-0">Michael Chen</h6>
                                <small class="text-muted">Software Engineer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="card-text">"Easy to use interface with beautiful templates. The live preview feature helped me perfect every detail."</p>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('assets/user3.jpg') }}" alt="User" class="rounded-circle me-3" width="50" style="object-fit: cover; height: 50px;">
                            <div>
                                <h6 class="mb-0">Emily Davis</h6>
                                <small class="text-muted">HR Specialist</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="display-5 fw-bold mb-4">Ready to Create Your Professional Resume?</h2>
        <p class="lead mb-4">Join thousands of professionals who have landed their dream jobs with CVBoost.id</p>
        <a href="{{ route('resume.create') }}" class="btn btn-light btn-lg">
            <i class="fas fa-plus"></i> Get Started Now - It's Free!
        </a>
        <p class="mt-3 mb-0">No credit card required • Create up to 3 free resumes</p>
    </div>
</section>

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.feature-icon {
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.template-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.template-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.template-thumbnail {
    height: 200px;
    object-fit: cover;
}

.step-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #3490dc;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 24px;
    font-weight: bold;
}

.step-number {
    background: transparent;
    border: 3px solid white;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

@media (max-width: 768px) {
    .hero-section .display-4 {
        font-size: 2rem;
    }
    
    .hero-section .d-flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .hero-section .btn-lg {
        width: 100%;
    }
}
</style>
@endsection

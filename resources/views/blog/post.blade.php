@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Article Header -->
            <article class="blog-post">
                <header class="mb-4">
                    <h1 class="display-5 fw-bold mb-3">{{ $post['title'] }}</h1>
                    <div class="d-flex align-items-center text-muted mb-4">
                        <img src="{{ asset('assets/author-avatar.jpg') }}" alt="Author" class="rounded-circle me-3" width="40" style="object-fit:cover; height:40px;">
                        <div>
                            <div>{{ $post['author'] }}</div>
                            <small>{{ $post['date'] }} • 5 min read</small>
                        </div>
                    </div>
                </header>
                
                <!-- Featured Image -->
                <div class="mb-4">
                    <img src="{{ asset('assets/blog/' . $post['slug'] . '.jpg') }}" 
                         alt="{{ $post['title'] }}" 
                         class="img-fluid rounded shadow">
                </div>
                
                <!-- Article Content -->
                <div class="blog-content">
                    {!! $post['content'] !!}
                </div>
                
                <!-- Article Tags -->
                <div class="mt-4">
                    <h6>Tags:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-primary">Resume</span>
                        <span class="badge bg-primary">ATS</span>
                        <span class="badge bg-primary">Job Search</span>
                        <span class="badge bg-primary">Career Tips</span>
                    </div>
                </div>
                
                <!-- Share Buttons -->
                <div class="mt-4">
                    <h6>Share this article:</h6>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="shareArticle('linkedin')">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="shareArticle('twitter')">
                            <i class="fab fa-twitter"></i> Twitter
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="shareArticle('facebook')">
                            <i class="fab fa-facebook"></i> Facebook
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="copyLink()">
                            <i class="fas fa-link"></i> Copy Link
                        </button>
                    </div>
                </div>
            </article>
            
            <!-- Author Bio -->
            <div class="card mt-5">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('assets/author-avatar.jpg') }}" alt="Author" class="rounded-circle me-3" width="60" style="object-fit:cover; height:60px;">
                        <div>
                            <h6 class="mb-1">About {{ $post['author'] }}</h6>
                            <p class="text-muted small mb-0">
                                Career expert and resume specialist with over 10 years of experience helping professionals land their dream jobs. Passionate about ATS optimization and modern job search strategies.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Related Articles -->
            <div class="mt-5">
                <h4>Related Articles</h4>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="row g-0">
                                <div class="col-4">
                                    <img src="{{ asset('assets/blog/resume-formatting.jpg') }}" 
                                         alt="Related article" 
                                         class="img-fluid rounded-start h-100 object-fit-cover">
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h6 class="card-title small">The Ultimate Guide to Resume Formatting</h6>
                                        <a href="{{ route('blog.post', 'resume-formatting-guide') }}" class="btn btn-primary btn-sm">
                                            Read More
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="row g-0">
                                <div class="col-4">
                                    <img src="{{ asset('assets/blog/resume-mistakes.jpg') }}" 
                                         alt="Related article" 
                                         class="img-fluid rounded-start h-100 object-fit-cover">
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h6 class="card-title small">Common Resume Mistakes to Avoid</h6>
                                        <a href="{{ route('blog.post', 'common-resume-mistakes') }}" class="btn btn-primary btn-sm">
                                            Read More
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Table of Contents -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Table of Contents</h6>
                </div>
                <div class="card-body">
                    <nav class="toc">
                        <ul class="list-unstyled">
                            <li><a href="#section1" class="toc-link">What is an ATS?</a></li>
                            <li><a href="#section2" class="toc-link">Why ATS Optimization Matters</a></li>
                            <li><a href="#section3" class="toc-link">10 ATS Optimization Tips</a></li>
                            <li><a href="#section4" class="toc-link">Common ATS Mistakes</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            
            <!-- Newsletter Signup -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Newsletter</h6>
                </div>
                <div class="card-body">
                    <p class="small">Get career tips and job search advice delivered to your inbox.</p>
                    <form id="sidebarNewsletterForm">
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Your email" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-envelope"></i> Subscribe
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Popular Posts -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Popular Posts</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('blog.post', 'write-professional-summary') }}" class="list-group-item list-group-item-action">
                            <h6 class="mb-1 small">How to Write a Professional Summary That Stands Out</h6>
                            <small class="text-muted">Mar 10, 2024</small>
                        </a>
                        <a href="{{ route('blog.post', 'resume-formatting-guide') }}" class="list-group-item list-group-item-action">
                            <h6 class="mb-1 small">The Ultimate Guide to Resume Formatting</h6>
                            <small class="text-muted">Mar 5, 2024</small>
                        </a>
                        <a href="{{ route('blog.post', 'common-resume-mistakes') }}" class="list-group-item list-group-item-action">
                            <h6 class="mb-1 small">Common Resume Mistakes to Avoid</h6>
                            <small class="text-muted">Feb 28, 2024</small>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- CTA Card -->
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h6 class="mb-3">Ready to Build Your Resume?</h6>
                    <p class="small mb-3">Create a professional resume that passes ATS with our easy-to-use builder.</p>
                    <a href="{{ route('resume.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Create Resume
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.blog-post {
    font-size: 1.1rem;
    line-height: 1.8;
}

.blog-content h2,
.blog-content h3,
.blog-content h4 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.blog-content ul,
.blog-content ol {
    margin-bottom: 1.5rem;
}

.blog-content li {
    margin-bottom: 0.5rem;
}

.blog-content blockquote {
    border-left: 4px solid #3490dc;
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: #6c757d;
}

.toc-link {
    color: #3490dc;
    text-decoration: none;
    font-size: 0.9rem;
}

.toc-link:hover {
    text-decoration: underline;
}

.toc-link.active {
    font-weight: 600;
    color: #0d6efd;
}

@media (max-width: 768px) {
    .blog-post {
        font-size: 1rem;
    }
}
</style>

@endsection

@push('scripts')
<script>
// Smooth scrolling for TOC links
document.querySelectorAll('.toc-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);
        
        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Newsletter form submission
document.getElementById('sidebarNewsletterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = this.querySelector('input[type="email"]').value;
    
    alert(`Thank you for subscribing with email: ${email}`);
    this.reset();
});

// Share functionality
function shareArticle(platform) {
    const url = window.location.href;
    const title = document.querySelector('h1').textContent;
    
    let shareUrl = '';
    
    switch(platform) {
        case 'linkedin':
            shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`;
            break;
        case 'twitter':
            shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`;
            break;
        case 'facebook':
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
            break;
    }
    
    if (shareUrl) {
        window.open(shareUrl, '_blank', 'width=600,height=400');
    }
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Link copied to clipboard!');
    });
}

// Highlight current section in TOC on scroll
window.addEventListener('scroll', function() {
    const sections = document.querySelectorAll('h2[id], h3[id]');
    const tocLinks = document.querySelectorAll('.toc-link');
    
    let currentSection = '';
    
    sections.forEach(section => {
        const rect = section.getBoundingClientRect();
        if (rect.top <= 100 && rect.bottom >= 100) {
            currentSection = section.id;
        }
    });
    
    tocLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${currentSection}`) {
            link.classList.add('active');
        }
    });
});
</script>
@endpush

@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">Career Blog</h1>
        <p class="lead text-muted">Expert tips and insights to help you land your dream job</p>
    </div>
    
    <!-- Featured Post -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="card featured-post shadow-lg">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="{{ asset('assets/blog/' . $posts[0]['image']) }}" 
                             alt="{{ $posts[0]['title'] }}" 
                             class="img-fluid rounded-start h-100 object-fit-cover">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="badge bg-primary">Featured</span>
                                <small class="text-muted ms-2">{{ $posts[0]['date'] }}</small>
                            </div>
                            <h3 class="card-title">{{ $posts[0]['title'] }}</h3>
                            <p class="card-text">{{ $posts[0]['excerpt'] }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">By {{ $posts[0]['author'] }}</small>
                                </div>
                                <a href="{{ route('blog.post', $posts[0]['slug']) }}" class="btn btn-primary">
                                    Read More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Blog Posts Grid -->
    <div class="row g-4">
        @foreach(array_slice($posts, 1) as $post)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 blog-card shadow-sm">
                    <img src="{{ asset('assets/blog/' . $post['image']) }}" 
                         alt="{{ $post['title'] }}" 
                         class="card-img-top blog-thumbnail">
                    <div class="card-body">
                        <div class="mb-2">
                            <small class="text-muted">{{ $post['date'] }}</small>
                        </div>
                        <h5 class="card-title">{{ $post['title'] }}</h5>
                        <p class="card-text">{{ $post['excerpt'] }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">By {{ $post['author'] }}</small>
                            <a href="{{ route('blog.post', $post['slug']) }}" class="btn btn-outline-primary btn-sm">
                                Read More
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Newsletter Signup -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="card bg-primary text-white">
                <div class="card-body text-center py-4">
                    <h4 class="mb-3">Get Career Tips Delivered to Your Inbox</h4>
                    <p class="mb-4">Join our newsletter for exclusive content and job search tips</p>
                    <form class="row g-3 justify-content-center">
                        <div class="col-md-6">
                            <input type="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-light w-100">
                                <i class="fas fa-envelope"></i> Subscribe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Categories -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Browse by Category</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-primary w-100">
                                <i class="fas fa-file-alt"></i> Resume Writing
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-primary w-100">
                                <i class="fas fa-search"></i> Job Search
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-primary w-100">
                                <i class="fas fa-comments"></i> Interview Tips
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-outline-primary w-100">
                                <i class="fas fa-chart-line"></i> Career Growth
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.featured-post {
    border: none;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.blog-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.blog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.blog-thumbnail {
    height: 200px;
    object-fit: cover;
}

.card-title {
    font-size: 1.1rem;
    font-weight: 600;
    line-height: 1.4;
}

.card-text {
    font-size: 0.9rem;
    color: #6c757d;
}

@media (max-width: 768px) {
    .blog-thumbnail {
        height: 150px;
    }
    
    .featured-post .img-fluid {
        height: 200px;
        object-fit: cover;
    }
}
</style>

@endsection

@push('scripts')
<script>
// Newsletter form submission
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = this.querySelector('input[type="email"]').value;
    
    // Simulate newsletter subscription
    alert(`Thank you for subscribing with email: ${email}`);
    this.reset();
});

// Category filtering (placeholder functionality)
document.querySelectorAll('.btn-outline-primary').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const category = this.textContent.trim();
        alert(`Filtering by category: ${category}`);
    });
});
</script>
@endpush

<footer class="bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <!-- Company Info -->
            <div class="col-lg-4 mb-4">
                <h5 class="mb-3">CVBoost.id</h5>
                <p class="text-muted">Create professional resumes that pass ATS with our AI-powered resume builder. Build stunning resumes in minutes with our expert-designed templates.</p>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="text-white"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="{{ route('templates.gallery') }}" class="text-muted text-decoration-none">Templates</a></li>
                    <li class="mb-2"><a href="{{ route('pricing') }}" class="text-muted text-decoration-none">Pricing</a></li>
                    <li class="mb-2"><a href="{{ route('blog') }}" class="text-muted text-decoration-none">Blog</a></li>
                    <li class="mb-2"><a href="{{ route('resume.create') }}" class="text-muted text-decoration-none">Create Resume</a></li>
                </ul>
            </div>
            
            <!-- Features -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="mb-3">Features</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-muted text-decoration-none">ATS Checker</a></li>
                    <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Professional Templates</a></li>
                    <li class="mb-2"><a href="#" class="text-muted text-decoration-none">PDF Export</a></li>
                    <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Live Preview</a></li>
                    <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Resume Analytics</a></li>
                </ul>
            </div>
            
            <!-- Support -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="mb-3">Support</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Help Center</a></li>
                    <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Contact Us</a></li>
                    <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Privacy Policy</a></li>
                    <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Terms of Service</a></li>
                    <li class="mb-2"><a href="#" class="text-muted text-decoration-none">FAQ</a></li>
                </ul>
            </div>
        </div>
        
        <hr class="border-secondary my-4">
        
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="text-muted mb-0">&copy; {{ date('Y') }} CVBoost.id. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="text-muted mb-0">
                    Made with <i class="fas fa-heart text-danger"></i> in Indonesia
                </p>
            </div>
        </div>
    </div>
</footer>

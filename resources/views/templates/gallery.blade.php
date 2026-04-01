@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">Professional Resume Templates</h1>
        <p class="lead text-muted">Choose from our collection of ATS-friendly templates designed by professionals</p>
    </div>
    
    <!-- Filter Options -->
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Filter by Type</label>
                            <select class="form-select" id="templateFilter">
                                <option value="all">All Templates</option>
                                <option value="free">Free Templates</option>
                                <option value="premium">Premium Templates</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sort by</label>
                            <select class="form-select" id="sortTemplates">
                                <option value="name">Name</option>
                                <option value="popular">Most Popular</option>
                                <option value="newest">Newest</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" id="searchTemplates" placeholder="Search templates...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Templates Grid -->
    <div class="row g-4" id="templatesContainer">
        @foreach($templates as $template)
            <div class="col-md-6 col-lg-4 template-item" 
                 data-type="{{ $template->is_premium ? 'premium' : 'free' }}"
                 data-name="{{ strtolower($template->name) }}">
                <div class="card h-100 template-card shadow-sm">
                    <div class="position-relative">
                        <img src="{{ asset($template->thumbnail) }}" 
                             alt="{{ $template->name }}" 
                             class="card-img-top template-thumbnail">
                        
                        @if($template->is_premium)
                            <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">
                                <i class="fas fa-crown"></i> PRO
                            </span>
                        @endif
                        
                        <!-- Quick Preview Button -->
                        <div class="template-overlay">
                            <button class="btn btn-primary btn-sm preview-btn" 
                                    data-template-id="{{ $template->id }}">
                                <i class="fas fa-eye"></i> Quick Preview
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $template->name }}</h5>
                        <p class="card-text text-muted small">
                            {{ $template->is_premium ? 'Premium template with advanced features and customization options' : 'Clean and professional design perfect for most industries' }}
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge {{ $template->is_premium ? 'bg-warning' : 'bg-success' }}">
                                {{ $template->is_premium ? 'Premium' : 'Free' }}
                            </span>
                            
                            @if($template->is_premium && (!auth()->user() || !auth()->user()->isPremium()))
                                <button class="btn btn-outline-primary btn-sm" disabled>
                                    <i class="fas fa-lock"></i> Premium Only
                                </button>
                            @else
                                <a href="{{ route('resume.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Use Template
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- No Results Message -->
    <div id="noResults" class="text-center py-5" style="display: none;">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h4>No templates found</h4>
        <p class="text-muted">Try adjusting your filters or search terms</p>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Template Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3">Loading preview...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="useTemplateBtn">
                    <i class="fas fa-plus"></i> Use This Template
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.template-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

.template-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.template-thumbnail {
    height: 200px;
    object-fit: cover;
}

.template-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.template-card:hover .template-overlay {
    opacity: 1;
}

.preview-btn {
    transform: translateY(20px);
    transition: transform 0.3s ease;
}

.template-card:hover .preview-btn {
    transform: translateY(0);
}

.template-item {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

#previewContent {
    min-height: 400px;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
}

@media (max-width: 768px) {
    .template-thumbnail {
        height: 150px;
    }
}
</style>

@endsection

@push('scripts')
<script>
// Filter functionality
document.getElementById('templateFilter').addEventListener('change', filterTemplates);
document.getElementById('sortTemplates').addEventListener('change', filterTemplates);
document.getElementById('searchTemplates').addEventListener('input', filterTemplates);

function filterTemplates() {
    const filterType = document.getElementById('templateFilter').value;
    const sortBy = document.getElementById('sortTemplates').value;
    const searchTerm = document.getElementById('searchTemplates').value.toLowerCase();
    
    const templates = document.querySelectorAll('.template-item');
    let visibleCount = 0;
    
    templates.forEach(template => {
        const type = template.dataset.type;
        const name = template.dataset.name;
        
        // Apply filters
        const typeMatch = filterType === 'all' || type === filterType;
        const searchMatch = name.includes(searchTerm);
        
        if (typeMatch && searchMatch) {
            template.style.display = 'block';
            visibleCount++;
        } else {
            template.style.display = 'none';
        }
    });
    
    // Show/hide no results message
    document.getElementById('noResults').style.display = visibleCount === 0 ? 'block' : 'none';
    
    // Apply sorting
    if (sortBy === 'name') {
        sortTemplatesByName();
    } else if (sortBy === 'popular') {
        sortTemplatesByPopularity();
    } else if (sortBy === 'newest') {
        sortTemplatesByNewest();
    }
}

function sortTemplatesByName() {
    const container = document.getElementById('templatesContainer');
    const templates = Array.from(container.querySelectorAll('.template-item'));
    
    templates.sort((a, b) => {
        const nameA = a.dataset.name;
        const nameB = b.dataset.name;
        return nameA.localeCompare(nameB);
    });
    
    templates.forEach(template => container.appendChild(template));
}

function sortTemplatesByPopularity() {
    // Premium templates first, then free
    const container = document.getElementById('templatesContainer');
    const templates = Array.from(container.querySelectorAll('.template-item'));
    
    templates.sort((a, b) => {
        const typeA = a.dataset.type;
        const typeB = b.dataset.type;
        return typeB.localeCompare(typeA); // premium > free
    });
    
    templates.forEach(template => container.appendChild(template));
}

function sortTemplatesByNewest() {
    // For demo purposes, just reverse the current order
    const container = document.getElementById('templatesContainer');
    const templates = Array.from(container.querySelectorAll('.template-item'));
    
    templates.reverse();
    templates.forEach(template => container.appendChild(template));
}

// Preview modal functionality
const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
let currentTemplateId = null;

document.querySelectorAll('.preview-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        currentTemplateId = this.dataset.templateId;
        loadTemplatePreview(currentTemplateId);
        previewModal.show();
    });
});

document.getElementById('useTemplateBtn').addEventListener('click', function() {
    if (currentTemplateId) {
        window.location.href = `/create?template=${currentTemplateId}`;
    }
});

function loadTemplatePreview(templateId) {
    const previewContent = document.getElementById('previewContent');
    
    fetch(`/api/templates/${templateId}/preview`)
        .then(response => response.json())
        .then(data => {
            previewContent.innerHTML = `
                <div class="preview-container p-3">
                    ${data.html}
                </div>
            `;
            
            // Apply custom styles
            const style = document.createElement('style');
            style.textContent = data.css;
            previewContent.appendChild(style);
        })
        .catch(error => {
            console.error('Error loading preview:', error);
            previewContent.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h5>Preview not available</h5>
                    <p class="text-muted">Please try again later</p>
                </div>
            `;
        });
}

// Template card click (use template)
document.querySelectorAll('.template-card').forEach(card => {
    card.addEventListener('click', function(e) {
        // Don't trigger if clicking on preview button
        if (!e.target.closest('.preview-btn')) {
            const templateItem = this.closest('.template-item');
            const isPremium = templateItem.dataset.type === 'premium';
            
            if (isPremium && (!{{ auth()->user() ? 'true' : 'false' }} || !{{ auth()->user()?->isPremium() ? 'true' : 'false' }})) {
                // Show upgrade modal for premium templates
                alert('This template requires a premium subscription. Upgrade to unlock all templates!');
                return;
            }
            
            window.location.href = '{{ route("resume.create") }}';
        }
    });
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    filterTemplates();
});
</script>
@endpush

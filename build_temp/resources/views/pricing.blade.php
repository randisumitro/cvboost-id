@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">Choose Your Plan</h1>
        <p class="lead text-muted">Select the perfect plan for your career needs</p>
    </div>
    
    <!-- Pricing Cards -->
    <div class="row g-4 align-items-start">
        <!-- Free Plan -->
        <div class="col-lg-4">
            <div class="card h-100 pricing-card">
                <div class="card-header text-center bg-light">
                    <h4 class="card-title">Free</h4>
                    <div class="price">
                        <span class="currency">Rp</span>
                        <span class="amount">0</span>
                        <span class="period">/month</span>
                    </div>
                    <p class="text-muted">Perfect for getting started</p>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Create up to 3 resumes
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            3 free templates
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Basic ATS checker (3 scans)
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            PDF download with watermark
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-times text-danger me-2"></i>
                            <del>Premium templates</del>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-times text-danger me-2"></i>
                            <del>DOCX export</del>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-times text-danger me-2"></i>
                            <del>Priority support</del>
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-center">
                    @if(!auth()->user() || auth()->user()->subscription_status === 'free')
                        <button class="btn btn-outline-primary btn-lg w-100" disabled>
                            <i class="fas fa-check"></i> Current Plan
                        </button>
                    @else
                        <a href="{{ route('subscription.downgrade') }}" class="btn btn-outline-secondary btn-lg w-100">
                            Downgrade
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Premium Plan -->
        <div class="col-lg-4">
            <div class="card h-100 pricing-card popular">
                <div class="popular-badge">
                    <i class="fas fa-crown"></i> MOST POPULAR
                </div>
                <div class="card-header text-center bg-primary text-white">
                    <h4 class="card-title">Premium</h4>
                    <div class="price">
                        <span class="currency">Rp</span>
                        <span class="amount">49,000</span>
                        <span class="period">/month</span>
                    </div>
                    <p class="text-white-50">Best value for professionals</p>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Unlimited resumes</strong>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>All 10+ templates</strong>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Unlimited ATS scans</strong>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>No watermark</strong>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>DOCX export</strong>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Priority support</strong>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Advanced customization</strong>
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-center">
                    @if(auth()->user() && auth()->user()->isPremium())
                        <button class="btn btn-success btn-lg w-100" disabled>
                            <i class="fas fa-check"></i> Active Plan
                        </button>
                    @else
                        <button class="btn btn-success btn-lg w-100" onclick="subscribe('monthly')">
                            <i class="fas fa-rocket"></i> Upgrade Now
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Annual Plan -->
        <div class="col-lg-4">
            <div class="card h-100 pricing-card">
                <div class="savings-badge">
                    Save 20%
                </div>
                <div class="card-header text-center bg-success text-white">
                    <h4 class="card-title">Annual</h4>
                    <div class="price">
                        <span class="currency">Rp</span>
                        <span class="amount">490,000</span>
                        <span class="period">/year</span>
                    </div>
                    <p class="text-white-50">Best savings for long-term</p>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Everything in Premium</strong>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Save Rp 98,000/year</strong>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Pay monthly equivalent: Rp 40,833</strong>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Priority support</strong>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Early access to new features</strong>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Exclusive templates</strong>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            <strong>Resume analytics</strong>
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-center">
                    @if(auth()->user() && auth()->user()->isPremium())
                        <button class="btn btn-success btn-lg w-100" disabled>
                            <i class="fas fa-check"></i> Active Plan
                        </button>
                    @else
                        <button class="btn btn-success btn-lg w-100" onclick="subscribe('yearly')">
                            <i class="fas fa-rocket"></i> Upgrade Now
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Feature Comparison -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Feature Comparison</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Feature</th>
                                    <th class="text-center">Free</th>
                                    <th class="text-center">Premium</th>
                                    <th class="text-center">Annual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Number of Resumes</td>
                                    <td class="text-center">3</td>
                                    <td class="text-center"><i class="fas fa-infinity text-success"></i> Unlimited</td>
                                    <td class="text-center"><i class="fas fa-infinity text-success"></i> Unlimited</td>
                                </tr>
                                <tr>
                                    <td>Templates Available</td>
                                    <td class="text-center">3</td>
                                    <td class="text-center">10+</td>
                                    <td class="text-center">10+</td>
                                </tr>
                                <tr>
                                    <td>ATS Checker Scans</td>
                                    <td class="text-center">3</td>
                                    <td class="text-center"><i class="fas fa-infinity text-success"></i> Unlimited</td>
                                    <td class="text-center"><i class="fas fa-infinity text-success"></i> Unlimited</td>
                                </tr>
                                <tr>
                                    <td>PDF Watermark</td>
                                    <td class="text-center"><i class="fas fa-check text-danger"></i> Yes</td>
                                    <td class="text-center"><i class="fas fa-times text-success"></i> No</td>
                                    <td class="text-center"><i class="fas fa-times text-success"></i> No</td>
                                </tr>
                                <tr>
                                    <td>DOCX Export</td>
                                    <td class="text-center"><i class="fas fa-times text-danger"></i> No</td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i> Yes</td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i> Yes</td>
                                </tr>
                                <tr>
                                    <td>Priority Support</td>
                                    <td class="text-center"><i class="fas fa-times text-danger"></i> No</td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i> Yes</td>
                                    <td class="text-center"><i class="fas fa-check text-success"></i> Yes</td>
                                </tr>
                                <tr>
                                    <td>Custom Colors</td>
                                    <td class="text-center">5 options</td>
                                    <td class="text-center"><i class="fas fa-infinity text-success"></i> Unlimited</td>
                                    <td class="text-center"><i class="fas fa-infinity text-success"></i> Unlimited</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="text-center mb-4">
                <h3>Frequently Asked Questions</h3>
            </div>
            
            <div class="accordion" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            Can I change my plan anytime?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes! You can upgrade or downgrade your plan at any time. If you downgrade, you'll keep access to premium features until the end of your current billing period.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                            What payment methods do you accept?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            We accept all major payment methods including credit cards, bank transfers (VA), QRIS, and digital wallets through our secure payment gateway.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                            Is my data secure?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Absolutely! We use industry-standard encryption and security measures to protect your personal information. Your data is never shared with third parties without your consent.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                            Can I cancel my subscription?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            Yes, you can cancel your subscription at any time. You'll continue to have access to premium features until the end of your current billing period.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CTA Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body text-center py-4">
                    <h4 class="mb-3">Ready to upgrade your career?</h4>
                    <p class="mb-4">Join thousands of professionals who have landed their dream jobs with CVBoost.id</p>
                    <button class="btn btn-light btn-lg" onclick="subscribe('monthly')">
                        <i class="fas fa-rocket"></i> Start Free Trial
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.pricing-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
}

.pricing-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

.pricing-card.popular {
    transform: scale(1.05);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.popular-badge {
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 5px 20px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    z-index: 1;
}

.savings-badge {
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    background: #28a745;
    color: white;
    padding: 5px 20px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    z-index: 1;
}

.price {
    font-size: 3rem;
    font-weight: bold;
    margin: 20px 0;
}

.currency {
    font-size: 1.5rem;
    vertical-align: top;
}

.amount {
    font-size: 3rem;
}

.period {
    font-size: 1rem;
    opacity: 0.8;
}

.pricing-card .card-header {
    border-bottom: none;
    padding: 2rem 1rem;
}

.pricing-card .card-footer {
    border-top: none;
    padding: 1.5rem;
}

@media (max-width: 992px) {
    .pricing-card.popular {
        transform: scale(1);
    }
    
    .price {
        font-size: 2rem;
    }
    
    .amount {
        font-size: 2rem;
    }
}
</style>

@endsection

@push('scripts')
<script>
function subscribe(duration) {
    @if(!auth()->user())
        // Redirect to login if not authenticated
        window.location.href = '{{ route("login") }}?redirect={{ route("pricing") }}';
    @else
        // Create subscription via API
        fetch('/api/subscription/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                duration: duration
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.payment_url) {
                window.location.href = data.payment_url;
            } else {
                alert('Error creating subscription. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error creating subscription. Please try again.');
        });
    @endif
}
</script>
@endpush

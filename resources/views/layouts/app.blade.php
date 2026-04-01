<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO Meta Tags -->
        <title>{{ isset($seo_title) ? $seo_title : config('app.name', 'CVBoost.id') }}</title>

        @isset($seo_description)
        <meta name="description" content="{{ $seo_description }}">
        @else
        <meta name="description" content="Create professional resumes that pass ATS with CVBoost.id. Build stunning resumes in minutes with our AI-powered ATS checker and professional templates.">
        @endisset

        @isset($seo_keywords)
        <meta name="keywords" content="{{ $seo_keywords }}">
        @else
        <meta name="keywords" content="resume builder, ATS checker, professional templates, CV maker, job search, career, resume templates, ATS optimization">
        @endisset

        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="{{ isset($seo_title) ? $seo_title : config('app.name', 'CVBoost.id') }}">
        <meta property="og:description" content="{{ isset($seo_description) ? $seo_description : 'Create professional resumes that pass ATS with CVBoost.id' }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
        <meta property="og:site_name" content="CVBoost.id">

        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ isset($seo_title) ? $seo_title : config('app.name', 'CVBoost.id') }}">
        <meta name="twitter:description" content="{{ isset($seo_description) ? $seo_description : 'Create professional resumes that pass ATS with CVBoost.id' }}">
        <meta name="twitter:image" content="{{ asset('images/og-image.jpg') }}">

        <!-- Canonical URL -->
        <link rel="canonical" href="{{ url()->current() }}">

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Google Fonts (for resume templates) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Arial:wght@400;700&family=Times+New+Roman:wght@400;700&display=swap" rel="stylesheet">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Custom CSS -->
        @vite(['resources/css/app.css'])

        <!-- Schema.org Structured Data -->
        @if(request()->routeIs('/'))
        <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "WebApplication",
            "name": "CVBoost.id",
            "description": "Create professional resumes that pass ATS with our AI-powered resume builder",
            "url": "{{ url('/') }}",
            "applicationCategory": "BusinessApplication",
            "operatingSystem": "Web Browser",
            "offers": {
                "@@type": "Offer",
                "price": "0",
                "priceCurrency": "IDR"
            }
        }
        </script>
        @endif

        <!-- Google Analytics (placeholder) -->
        @if(config('app.env') === 'production')
        <script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'GA_MEASUREMENT_ID');
        </script>
        @endif
    </head>
    <body class="font-sans antialiased">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            <!-- Banner Ad for non-premium users -->
            @if(!auth()->user() || !auth()->user()->isPremium())
                @if(!request()->routeIs(['login', 'register']))
                    @include('components.ads', ['type' => 'banner'])
                @endif
            @endif

            @yield('content')
            {{ $slot ?? '' }}
        </main>

        <!-- Footer Ad -->
        @if(!auth()->user() || !auth()->user()->isPremium())
            @if(!request()->routeIs(['login', 'register']))
                @include('components.ads', ['type' => 'footer'])
            @endif
        @endif

        <!-- Footer -->
        @include('layouts.footer')

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @vite(['resources/js/app.js'])

        <!-- Page tracking (disabled for local testing) -->
        @if(false)
        <script>
        // Track page view
        fetch('/api/track/view', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                page_url: window.location.href,
                resume_id: {{ isset($resume) ? $resume->id : 'null' }}
            })
        }).catch(error => console.log('Tracking failed:', error));
        </script>
        @endif
        
        @stack('scripts')
    </body>
</html>

{{-- DEPRECATED: This file is no longer used. The API now uses Blade templates directly. --}}
{{-- All PDF generation now uses: resume.templates.{slug} --}}
@php
    // This view is deprecated and should not be used.
    // The API PDF endpoint now uses resume.templates.{slug} directly.
    abort(404, 'This view is deprecated. PDF generation now uses Blade templates directly.');
@endphp

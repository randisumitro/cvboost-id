@if(config('app.env') === 'production')
{{-- Banner Ad --}}
@if($type === 'banner')
    <div class="ad-container text-center my-4">
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-XXXXXXXXXXXXXXXX"
             data-ad-slot="XXXXXXXXXX"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
    </div>
@endif

{{-- Sidebar Ad --}}
@if($type === 'sidebar')
    <div class="ad-container mb-4">
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-XXXXXXXXXXXXXXXX"
             data-ad-slot="XXXXXXXXXX"
             data-ad-format="rectangle"
             data-full-width-responsive="true"></ins>
    </div>
@endif

{{-- Between Steps Ad --}}
@if($type === 'between-steps')
    <div class="ad-container text-center my-4 p-3 bg-light rounded">
        <p class="text-muted small mb-2">Advertisement</p>
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-XXXXXXXXXXXXXXXX"
             data-ad-slot="XXXXXXXXXX"
             data-ad-format="fluid"
             data-ad-layout-key="-fb+5w+4e-db+86"
             data-full-width-responsive="true"></ins>
    </div>
@endif

{{-- Footer Ad --}}
@if($type === 'footer')
    <div class="ad-container text-center py-4">
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-XXXXXXXXXXXXXXXX"
             data-ad-slot="XXXXXXXXXX"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
    </div>
@endif

{{-- Interstitial Ad --}}
@if($type === 'interstitial')
    <div class="modal fade" id="adModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Advertisement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="text-muted small mb-3">Please support us by viewing this ad</p>
                    <ins class="adsbygoogle"
                         style="display:block"
                         data-ad-client="ca-pub-XXXXXXXXXXXXXXXX"
                         data-ad-slot="XXXXXXXXXX"
                         data-ad-format="auto"
                         data-full-width-responsive="true"></ins>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Continue</button>
                </div>
            </div>
        </div>
    </div>
@endif

@if(config('app.env') === 'production')
<script>
(function() {
    // Track ad impression
    const adType = '{{ $type }}';
    const pageUrl = window.location.href;
    
    fetch('/api/track/ad-impression', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            ad_type: adType,
            page_url: pageUrl
        })
    }).catch(error => console.log('Ad tracking failed:', error));
})();
</script>

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-XXXXXXXXXXXXXXXX"
     crossorigin="anonymous"></script>

<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
@endif
@endif

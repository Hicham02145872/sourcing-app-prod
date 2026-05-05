<!-- Facebook Pixel Event Tracker -->
<!-- Usage in any Blade file:
    @push('scripts')
        <x-facebook-pixel-event event="Purchase" :data="['value' => 99.99, 'currency' => 'USD']"/>
    @endpush

    Or for global events, add to app.blade.php:
    <x-facebook-pixel-event event="CompleteRegistration" />
-->

@props([
    'event' => 'PageView',
    'data' => []
])

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof fbq !== 'undefined') {
            @if(!empty($data))
                fbq('track', '{{ $event }}', {!! json_encode($data) !!});
            @else
                fbq('track', '{{ $event }}');
            @endif
        } else {
            console.warn('Facebook Pixel (fbq) not loaded. Event "{{ $event }}" was not tracked.');
        }
    });
</script>

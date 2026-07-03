@php
    $flashPayload = array_filter([
        'success' => session('success'),
        'error' => session('error'),
        'warning' => session('warning'),
    ], fn ($value) => filled($value));
@endphp

@if (! empty($flashPayload))
    <script type="application/json" id="admin-flash-data">@json($flashPayload)</script>
@endif

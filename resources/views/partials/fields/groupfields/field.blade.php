@php
    $label = $label ?? '';
    $value = $value ?? '';
    $isLink = $isLink ?? false;
    $linkPrefix = $linkPrefix ?? '';
    $col = $col ?? 'col-4';
@endphp
<div class="{{ $col }}">
    <p>{{ $label }}</p>
    <h6 class="fw-bold">
        @if($isLink && $value)
            <a href="{{ $linkPrefix }}{{ $value }}">{{ $value }}</a>
        @else
            {{ $value ?: $label . ' Not Set' }}
        @endif
    </h6>
</div>

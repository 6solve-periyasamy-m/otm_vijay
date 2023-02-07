<a {{ $attributes->class(['popup-grid-item']) }}>
    <div class="icon">
        <i class="icon-{{ $icon }}"></i>
    </div>
    <div class="text">
        {{ $slot }}
    </div>
</a>

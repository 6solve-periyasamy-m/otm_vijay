<a {{ $attributes->class(['popup-grid-item']) }}>
    <div class="icon">
        {!! $icon !!}
    </div>
    <div class="text">
        {{ $slot }}
    </div>
</a>

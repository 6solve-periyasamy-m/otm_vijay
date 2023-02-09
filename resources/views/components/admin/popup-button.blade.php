<a {{ $attributes->class(['popup-grid-item']) }}>
    <div class="icon">
        {{ Icon::new($icon) }}
    </div>
    <div class="text">
        {{ $slot }}
    </div>
</a>

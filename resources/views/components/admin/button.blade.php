<div class="col-12 col-xl-{{ $attributes->get('width', 12) }}">
    <a href="{{ $attributes->get('href', 'javascript:{}') }}" class="btn btn-{{ $attributes->get('color', 'primary') }} w-100">
        {{ $slot }}
    </a>
</div>

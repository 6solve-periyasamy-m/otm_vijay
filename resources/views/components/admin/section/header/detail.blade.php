<div class="col-12 col-xl-{{ $attributes->get('width', 12) }}">
    <p>{{ $title }}</p>
    @if ($attributes->has('raw'))
        {{ $slot }}
    @else
        <h6 class="fw-bold">{!! $slot !!}</h6>
    @endif
</div>

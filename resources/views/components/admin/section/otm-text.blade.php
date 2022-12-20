<div class="{{ $attributes->has('width') ? "col-xl-{$attributes->get('width')}" : "" }}">
    <p {{ $header->attributes->class([]) }}>{{ $header }}</p>
    <h6 {{ $attributes->merge(['class' => 'fw-bold',]) }}>{!! $slot !!}</h6>
</div>

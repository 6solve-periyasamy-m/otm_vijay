@php $active = $attributes->get('active', 1) == 1; @endphp
@if($active)
    <a {{ $attributes->class(['btn', 'btn-sm', ]) }}>
        {{ $slot }}
    </a>
@else
    <span {{ $attributes->except(['class', 'href']) }} class="btn btn-sm btn-outline-dark">
        {{ $slot }}
    </span>
@endif

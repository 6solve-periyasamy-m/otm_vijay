@php
    $active = $attributes->get('active', 1) == 1;
    $uid = Str::uuid();
@endphp
@if($active)
    <a {{ $attributes->class(['btn', 'btn-sm', ])->except('href') }} href="#" onclick="event.preventDefault();document.getElementById('{{ $uid }}').submit();">
        {{ $slot }}
    </a>
    <form class="d-none" id="{{$uid}}" action="{{ $attributes->get('href') }}" method="post">
        @csrf
    </form>
@else
    <span {{ $attributes->except(['class', 'href']) }} class="btn btn-sm btn-outline-dark">
        {{ $slot }}
    </span
@endif

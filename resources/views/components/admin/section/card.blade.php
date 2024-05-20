@props(['header' => null, 'title' => null])
@if($attributes->has('width'))
<div class="col-xl-{{ $attributes->get('width', 12) }}">
@endif
    <div class="card">
        <div {{ $attributes->class(['card-body']) }}>
            @if(isset($header) || isset($title))
                <div class="card-title">
                    @if(isset($title))
                        <h4 class="fw-bold">{{ $title }}</h4>
                    @else
                        {{ $header }}
                    @endif
                </div>
            @endif
            {{ $slot }}
        </div>
    </div>
@if($attributes->has('width'))
</div>
@endif

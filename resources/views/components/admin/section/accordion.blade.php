@php
$id = $attributes->get('id', \Str::uuid());
$open = $attributes->get('open', true);
@endphp
<div>
    <div class="card">
        <div class="card-body" data-target="#{{$id}}" onclick="toggleAccordion(this)">
            <div class="d-flex justify-content-between">
                <div>
                    <h4 class="fw-bold">
                        {{ $open ? Icon::minimize() : Icon::maximize() }} {{ $title }}
                    </h4>
                </div>
                @if(isset($header_end))
                <div>
                    {{ $header_end }}
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="collapse {{ $open ? 'show' : '' }} mx-1" id="{{$id}}">
        {{ $slot }}
    </div>
</div>

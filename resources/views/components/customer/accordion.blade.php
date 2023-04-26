<div class="accordion @if($attributes->has('hide')) hidden @endif" @if($attributes->has('nobg')) style="box-shadow: none;" @endif>
    <div class="card card-heading accordion-header">
        <div class="card-body accordion-button" data-bs-toggle="collapse" data-bs-target="#{{ $attributes->get('id', 'accordion') }}" aria-expanded="{{ $attributes->has('hidden') ? 'false' : 'true' }}" aria-controls="{{ $attributes->get('id', 'accordion') }}">
            {{ $header }}
        </div>
    </div>
    @if($attributes->has('nocontainer'))
        <div class="accordion-collapse {{ $attributes->has('hidden') ? 'collapse' : 'show' }}" id="{{ $attributes->get('id') }}">
            {{ $slot }}
        </div>
    @else
        <div class="card accordion-collapse {{ $attributes->has('hidden') ? 'collapse' : 'show' }}" id="{{ $attributes->get('id') }}">
            <div class="card-body">
                {{ $slot }}
            </div>
        </div>
    @endif
</div>

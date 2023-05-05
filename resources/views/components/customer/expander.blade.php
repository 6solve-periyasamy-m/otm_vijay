<div class="accordion" @if($attributes->has('nobg')) style="box-shadow: none;" @endif>
    <div class="card card-heading accordion-header">
        <div class="card-body accordion-button" data-target="#{{ $attributes->get('id', 'accordion') }}" onclick="toggleExpander(this)" aria-expanded="{{ $attributes->has('expanded') ? 'true' : 'false' }}" aria-controls="{{ $attributes->get('id', 'accordion') }}">
            {{ $header }}
        </div>
    </div>
    <div id="{{ $attributes->get('id') }}" class="flex-scroll {{ $attributes->has('expanded') ? 'flex-wrap' : '' }}">
        {{ $slot }}
    </div>
</div>

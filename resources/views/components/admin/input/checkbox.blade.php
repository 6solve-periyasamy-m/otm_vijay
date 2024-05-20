<div class="form-group col-12 col-xl-{{ $attributes->get('width', 12) }}">
    <input type="checkbox" name="{{ $attributes->get('name') }}" class="form-check-input {{ $classes ?? '' }}" id="{{ $attributes->get('name') }}-input"
           @if((old($attributes->get('name')) != null && old($attributes->get('name')) == 'on') || $attributes->get('value', false)) checked @endif
           @if($attributes->has('disabled')) disabled @endif />
    <label for="{{ $attributes->get('name') }}-input" class="form-check-label">{{ $slot }}</label>
</div>

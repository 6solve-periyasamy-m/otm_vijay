<div class="form-group col-12 col-xl-{{ $attributes->get('width', 12) }}">
    <input type="checkbox" {{ $attributes->except('width')->merge(['type' => 'checkbox', 'class' => "form-checkbox float-padding {$attributes->get('name')}-input", 'id' => $attributes->get('name'), 'placeholder' => 'placeholder',]) }}
           @if((old($attributes->get('name')) != null && old($attributes->get('name')) == 'on') || $attributes->get('value', false)) checked @endif
    <label for="{{ $attributes->get('name') }}-input" class="form-check-label">{{ $slot }}</label>
</div>

<div class="form-floating form-group col-12 col-xl-{{ $width }}">
    <input {{ $attributes->merge(['class' => "form-control float-padding {$attributes->get('name')}-input", 'id' => $attributes->get('name'), 'placeholder' => 'placeholder',]) }} {{ $disable ? 'disabled' : '' }}/>
    <label for="{{ $attributes->get('id') }}">{{ $slot }}</label>
</div>

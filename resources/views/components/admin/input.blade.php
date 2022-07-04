<div class="form-group form-floating col-12 col-xl-{{ $attributes->get('width', 12) }} {{ $divClasses ?? "" }}" style="padding-left: 5px;">
    <input type="{{ $attributes->get('type', 'text') }}"
           class="form-control {{ $attributes->get('name') }}-input"
           style="padding-top: 15px"
           id="{{ $attributes->get('name') }}"
           name="{{ $attributes->get('name') }}"
           placeholder="placeholder"
           value="{{ old( $attributes->get('name')) ?? $attributes->get('value', '') }}"
           {{ $attributes->has('autocomplete') ? "autocomplete=\"{$attributes->get('autocomplete')}\"" : '' }}
           {{ $attributes->has('required') ? 'required' :  ''}} @if($attributes->has('onchange')) onchange="{{ $onChange }}" @endif>
    <label for="{{ $attributes->get('name') }}" style="padding-top: 10px;">{{ $slot }}</label>
</div>

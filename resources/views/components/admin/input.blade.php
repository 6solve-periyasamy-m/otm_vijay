<div class="form-group @if(!$attributes->has('nofloat')) form-floating @endif col-12 col-xl-{{ $attributes->get('width', 12) }} {{ $divClasses ?? "" }}" style="padding-left: 5px;">
    <input type="{{ $attributes->get('type', 'text') }}"
           class="form-control {{ $attributes->get('name') }}-input"
           @if(!$attributes->has('nofloat')) style="padding-top: 15px" @endif
           id="{{ $attributes->get('name') }}"
           name="{{ $attributes->get('name') }}"
           placeholder="placeholder"
           value="{{ old( $attributes->get('name')) ?? $attributes->get('value', '') }}"
           {{ $attributes->has('autocomplete') ? "autocomplete=\"{$attributes->get('autocomplete')}\"" : '' }}
           {{ $attributes->has('required') ? 'required' :  ''}}
           @if($attributes->has('onchange')) onchange="{{ $attributes->get('onchange') }}" @endif>
    @if(!$attributes->has('nofloat'))<label for="{{ $attributes->get('name') }}" style="padding-top: 10px">{{ $slot }}</label>@endif
</div>

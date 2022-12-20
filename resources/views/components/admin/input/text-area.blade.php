<div class="form-group form-floating col-12 col-xl-{{ $attributes->get('width', 12) }} {{ $divClasses ?? "" }}" style="padding-left: 5px;">
    <textarea class="form-control {{ $attributes->get('name') }}-input"
              id="{{ $attributes->get('name') }}"
              name="{{ $attributes->get('name') }}"
              rows="{{ $attributes->get('rows', 3) }}"
              placeholder="placeholder"
              {{ $attributes->has('autocomplete') ? "autocomplete=\"{$attributes->get('autocomplete')}\"" : '' }}
              {{ $attributes->has('required') ? 'required' :  ''}}
              @if($attributes->has('onchange')) onchange="{{ $onChange }}" @endif
    >{!! old( $attributes->get('name')) ?? $attributes->get('value', '') !!} </textarea>
    <label for="{{ $attributes->get('name') }}" style="padding-top: 10px;">{{ $slot }}</label>
</div>

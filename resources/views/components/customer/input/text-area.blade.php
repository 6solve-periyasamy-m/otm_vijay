<div class="form-floating form-group col-12 col-xl-{{ $attributes->get('width', 12) }}">
    <textarea
           class="form-control {{ $attributes->get('name') }}-input" style="padding-top: 25px; padding-left: 5px;"
           id="{{ $attributes->get('name') }}"
           name="{{ $attributes->get('name') }}"
           placeholder="placeholder"
           rows="{{ $attributes->get('rows', 3) }}"
           {{ $attributes->get('disabled', false) ? 'disabled' : '' }}
           {{ $attributes->has('autocomplete') ? "autocomplete=\"{$attributes->get('autocomplete')}\"" : '' }}>{{ $attributes->get('value', '') }}</textarea>
    <label for="{{ $attributes->get('id') }}">{{ $slot }}</label>
</div>

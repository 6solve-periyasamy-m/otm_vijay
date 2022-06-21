<div class="form-floating form-group col-12 col-xl-{{ $attributes->get('width', 12) }}">
    <input type="{{ $attributes->get('type', 'text') }}"
           class="form-control {{ $attributes->get('name') }}-input" style="padding-top: 25px; padding-left: 5px;"
           id="{{ $attributes->get('name') }}"
           name="amount"
           placeholder="placeholder"
           value="{{ $attributes->get('value', '') }}"
           {{ $attributes->has('autocomplete') ? "autocomplete=\"{$attributes->get('autocomplete')}\"" : '' }}>
    <label for="{{ $attributes->get('id') }}">{{ $slot }}</label>
</div>

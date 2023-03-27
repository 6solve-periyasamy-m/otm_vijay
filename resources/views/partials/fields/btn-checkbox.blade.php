@php $checked = (old($field) != null && old($field) == 'on')||(isset($value) && $value == 1); @endphp
<a href="javascript:void(0)" id="{{ $field }}-button" onclick="event.preventDefault();swapButton(this, '{{ $field }}')" class="d-inline ms-1 btn {{ $checked ? "btn-success" : "btn-danger" }}">
    <x-icon icon="{{ $icon ?? 'list' }}" />
</a>
<input
        type="checkbox"
        name="{{ $field }}"
        class="form-check-input d-none {{ $classes ?? '' }}"
        id="{{ $field }}-input"
        @if($checked)
            checked
        @endif
        @if(isset($onChange))
            onchange="{{ $onChange }}"
        @endif
>

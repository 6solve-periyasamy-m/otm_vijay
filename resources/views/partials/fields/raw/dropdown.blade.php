@isset($name)<label for="{{ $field }}-input" class="{{ $labelClasses ?? "" }}">{{ $name }}</label>@endisset
<select class="form-select {{ $classes ?? "" }}" name="{{ $field }}" id="{{ $field }}-input" autocomplete="off">
@php($set = isset($selected))
@php($selected = ($selected ?? null))
@if(($null ?? false) === true)
    <option @if(($selected) === null) selected @php($set = true) @endif value="">Not Set</option>
@endif
@foreach($values as $key => $value)
    <option value="{{ $key }}" @if(!$set || ((($selected) ?? old($field)) === $key)) selected @php($set = true) @endif>{{ $value }}</option>
@endforeach
</select>

<label for="tour_component_type-input" class="{{ $labelClasses ?? "" }}">{{ $name }}</label>
<select class="form-select {{ $classes ?? "" }}" name="{{ $field }}" id="{{ $field }}-input" autocomplete="off">
@php($set = isset($selected))
@foreach($values as $key => $value)
    <option value="{{ $key }}" @if(!$set || (old($field) ?? $selected) == $key) selected @php($set = true) @endif>{{ $value }}</option>
@endforeach
</select>

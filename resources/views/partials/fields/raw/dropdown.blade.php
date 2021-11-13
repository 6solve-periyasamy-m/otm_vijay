<label for="tour_component_type-input" class="{{ $labelClasses ?? "" }}">{{ $name }}</label>
<select class="form-select {{ $classes ?? "" }}" name="{{ $field }}" id="{{ $field }}-input">
@php($set = false)
@foreach($values as $key => $value)
    <option value="{{ $key }}" @if(!$set) selected @php($set = true) @endif>{{ $value }}</option>
@endforeach
</select>

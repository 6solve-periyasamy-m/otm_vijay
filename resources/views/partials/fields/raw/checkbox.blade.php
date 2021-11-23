<input type="checkbox" name="{{ $field }}" class="form-check-input {{ $classes ?? '' }}" id="{{ $field }}-input"
       @if((old($field) != null && old($field) == 'on')||(isset($value) && $value == 1)) checked @endif
       @if(isset($onChange)) onchange="{{ $onChange }}" @endif>
<label for="{{ $field }}-input" class="form-check-label {{ $labelClasses ?? "" }}">{{ $name }}</label>

<input type="checkbox" name="{{ $field }}" class="form-check-input {{ $classes ?? '' }}" id="{{ $field }}-input"
       @if(isset($value) && $value == 1) checked @endif
       @if(isset($onChange)) onchange="{{ $onChange }}" @endif>
<label for="{{ $field }}-input" class="form-check-label">{{ $name }}</label>

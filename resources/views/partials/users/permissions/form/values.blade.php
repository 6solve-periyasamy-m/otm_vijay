<div class="form-group">
    <input type="checkbox" name="{{ $class }}-{{ $action }}" class="{{ $group }} {{ $class }} form-check-input" id="{{ $class }}-{{ $action }}"
           @if((old($class.'-'.$action) != null && old($class.'-'.$action) == 'on')||(isset($value) && $value == true)) checked @endif
           @if(isset($onChange)) onchange="{{ $onChange }}" @endif
           @cannot($action, '\\App\\Models\\' . $class) disabled @endcan>
</div>

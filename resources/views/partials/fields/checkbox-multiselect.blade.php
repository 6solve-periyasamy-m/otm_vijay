<div class="form-group">
    <label>{{ $name }}</label>
    <div class="checkbox-group">
        @foreach($options as $key => $value)
            <div class="form-check form-check-inline">
                <input type="checkbox" name="{{ $field }}[]" id="{{ $field }}_{{ $key }}" 
                       value="{{ $key }}" 
                       class="form-check-input ms-3"
                       {{ in_array($key, $selected) ? 'checked' : '' }}>
                <label class="form-check-label lh-lg ps-2" for="{{ $field }}_{{ $key }}">{{ $value }}</label>
            </div>
        @endforeach
    </div>
</div>

@push('styles')
<style>
    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    .form-check {
        flex: 1 1 calc(33.333% - 10px);
    }
</style>
@endpush

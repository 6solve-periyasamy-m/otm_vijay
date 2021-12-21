@push('header-ready')
    @include('partials.fields.selector.script', ['field' => $field, 'id' => $value ?? 0, 'additionalParams' => $additionalParams ?? "",])
@endpush
<div class="form-group col-12 {{ isset($width) ? 'col-xl-' . $width : '' }} {{ $divClasses ?? "" }}">
    <label for="{{ $field }}-input">{{ $name }}</label>
    <select style="width: 100%" name="{{ $field }}" class="form-control {{ $field }}-input" id="{{ $field }}-input"></select>
</div>

@push('header-ready')
    @include('partials.fields.selector.script', ['route' => $route, 'field' => $field, 'id' => $value, 'additionalParams' => $additionalParams ?? "",])
@endpush
<div class="form-group col-12 {{ isset($width) ? 'col-xl-' . $width : '' }} {{ $divClasses ?? "" }}">
    <label for="{{ $field }}-input">{{ $name }}</label>
    <div class="d-flex">
        <select class="form-control {{ $field }}-input" id="{{ $field }}-input" name="{{ $field }}"></select>
        <a href="{{ $createRoute }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
    </div>
</div>

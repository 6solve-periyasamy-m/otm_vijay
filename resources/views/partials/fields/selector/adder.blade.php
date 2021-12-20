@push('header-ready')
    @include('partials.fields.selector.script', ['field' => $field, 'id' => $value ?? 0, 'additionalParams' => $additionalParams ?? "",])
@endpush
<div class="form-group col-12 {{ isset($width) ? 'col-xl-' . $width : '' }} {{ $divClasses ?? "" }}">
    @isset($name)<label for="{{ $field }}-input">{{ $name }}</label>@endisset
    <div class="d-flex">
        <select class="form-control {{ $field }}-input" id="{{ $field }}-input" name="{{ $field }}"></select>
        <a href="{{ $createRoute }}" target="{{ $target ?? '_blank' }}" class="btn btn-success d-inline ms-1" onclick="{{$onclick ?? ''}}">+</a>
    </div>
</div>

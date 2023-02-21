@php $sanitized = str_replace('%', 'per', str_replace(']', 'cbr', str_replace('[', 'obr', $field))); @endphp
@push('header-ready')
    @include('partials.fields.selector.script', ['field' => $field . '[id]', 'sanitized' => $sanitized, 'fieldId' => "{$sanitized}-input", 'id' => $value ?? 0, 'additionalParams' => $additionalParams ?? "",])
@endpush
<div class="form-group col-12 {{ isset($width) ? 'col-xl-' . $width : '' }} {{ $divClasses ?? "" }}">
    @isset($name)
        <label for="{{ $sanitized }}-input">{{ $name }}</label>
    @endisset
    <div class="d-flex">
        <select class="form-control {{ $sanitized }}-input" id="{{ $id ?? $sanitized . '-field' }}"
                name="{{ $field }}[id]"></select>
        <a href="{{ $createRoute }}" target="{{ $target ?? '_blank' }}" class="btn btn-success d-inline ms-1"
           onclick="{{$onclick ?? ''}}">+</a>
        <a href="javascript:getUnknownCustomer('#{{$id ?? $sanitized . '-field'}}')"
           class="btn btn-info d-inline ms-1">{{ Icon::unknownCustomer() }}</a>
        @include('partials.fields.btn-checkbox', ['field' => "{$field}[travelling]", 'icon' => 'plane', 'value' => 1,])
        @include('partials.fields.btn-checkbox', ['field' => "{$field}[paying]", 'icon' => 'wallet', 'value' => 1,])
    </div>
</div>

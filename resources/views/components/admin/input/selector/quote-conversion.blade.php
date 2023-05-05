@php $sanitizedName = str_replace(']', 'cbr', str_replace('[', 'obr', $attributes->get('name', ''))); @endphp
<div class="form-group col-12 col-xl-{{ $attributes->get('width', 12) }} {{ $divClasses ?? "" }}">
    <div class="d-flex">
        <div class="form-floating w-100">
            <select class="form-select {{ $attributes->get('paying', 0) ? 'paying' : 'travelling' }} {{ $attributes->get('name', '') }}-input" id="{{ $sanitizedName }}"
                    name="{{ $attributes->get('name', '') }}"></select>
            <label for="{{ $attributes->get('name', '') }}" style="padding-top: 10px;">{{ $slot }}</label>
        </div>
        <a href="{{ $create ?? "" }}" target="{{ $create->attributes->get('target', '_blank') }}"
           class="btn btn-success d-inline ms-1" onclick="{{ $create->attributes->get('onclick', '') }}">+</a>
        <a href="javascript:getUnknownCustomer('#{{$sanitizedName}}', {{ $attributes->get('paying', 0) }})"
           class="btn btn-info d-inline ms-1">{{ Icon::unknownCustomer() }}</a>
    </div>
</div>
@push('header-stack')
    <script type="text/javascript">
        $(document).ready(function () {
            let {{ $sanitizedName }}Select = $('#{{$sanitizedName}}');
            {{ $sanitizedName }}Select.select2({
                placeholder: "Please Select a Value",
                ajax: {
                    url: '{{ $attributes->get('full-route') ?? route("api.{$attributes->get('route', '')}.select") }}',
                    data: function (params) {
                        return {
                            filter: params.term,
                            __api_token: '{{ Auth::user()->getCurrentToken()->token }}',
                        };
                    },
                    type: 'post',
                }
            });
        });
    </script>
@endpush

@php $sanitizedName = str_replace(']', 'cbr', str_replace('[', 'obr', $attributes->get('name', ''))); @endphp
<div class="form-group col-12 col-xl-{{ $attributes->get('width', 12) }} {{ $divClasses ?? "" }}">
    <div class="d-flex">
        <div class="form-floating w-100">
            <select class="form-select {{ $attributes->get('name', '') }}-input" id="{{ $sanitizedName }}" name="{{ $attributes->get('name', '') }}"></select>
            <label for="{{ $attributes->get('name', '') }}" style="padding-top: 10px;">{{ $slot }}</label>
        </div>
        <a href="{{ $create ?? "" }}" target="{{ $create->attributes->get('target', '_blank') }}" class="btn btn-success d-inline ms-1" onclick="{{ $create->attributes->get('onclick', '') }}">+</a>
    </div>
</div>
@push('header-stack')
<script type="text/javascript">
    $(document).ready(function () {
        let {{ $sanitizedName }}Select = $('#{{$sanitizedName}}');
        console.log({{ $sanitizedName }}Select);
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
        @if($attributes->get('value'))
        $.ajax({
            url: '{{ route('api.' . $route . '.selected', ['id' => old($attributes->get('name', '')) ?? $attributes->get('value', 0), ]) }}',
            type: 'post', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', }
        })
         .then(function (data) {
            {{ $sanitizedName }}Select.append(new Option(data.text, data.id, true, true)).trigger('change');

            {{ $sanitizedName }}Select.trigger({
                type: 'select2:select',
                params: { data: data, }
            });
        });
        @endif
    });
</script>
@endpush

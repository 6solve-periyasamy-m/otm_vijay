<div class="form-group col-12 col-xl-{{ $attributes->get('width', 12) }} {{ $divClasses ?? "" }}">
    <div class="form-floating w-100">
        <select class="form-select {{ $attributes->get('name', '') }}-input" id="{{ $attributes->get('name', '') }}" name="{{ $attributes->get('name', '') }}"></select>
        <label for="{{ $attributes->get('name', '') }}" style="padding-top: 10px;">{{ $slot }}</label>
    </div>
</div>
@push('header-stack')
    <script type="text/javascript">
        $(document).ready(function () {
            let {{ $attributes->get('name', '') }}Select = $('.{{ $attributes->get('name', '') }}-input');
            {{ $attributes->get('name', '') }}Select.select2({
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
                url: '{{ route('api.' . $attributes->get('route', '') . '.selected', ['id' => old($attributes->get('name', '')) ?? $attributes->get('value', 0), ]) }}',
                type: 'post', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', }
            })
                .then(function (data) {
                    {{ $attributes->get('name', '') }}Select.append(new Option(data.text, data.id, true, true)).trigger('change');

                    {{ $attributes->get('name', '') }}Select.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
            @endif
        });
    </script>
@endpush

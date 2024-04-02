@php
    $id = $attributes->get('id', Str::random());
    $route = $attributes->get('route');
    $allRoute = route('api.' . $route . '.select');
    $value = $attributes->get('value');
    $value = empty($value) ? null : $value;
    $clear = $attributes->get('clear', false);
    $updateRoute = null;
    if ($value !== null) {
        $updateRoute = route("api.{$route}.selected", ['id' => $value, ]);
    }

    $createRoute = $attributes->get('createRoute');
    $createForm = $attributes->get('createForm');

    if (isset($createRoute)) {
        $create = "window.location = '$createRoute';";
    }
    if (isset($createForm)) {
        $create = "openModal('$createForm');";
    }
    $create = $create ?? $attributes->get('create');
@endphp
<div wire:ignore class="form-group col-12 col-xl-{{ $attributes->get('width', 12) }}">
    <label for="{{ $id }}">{{ $attributes->get('label') }} @if($attributes->has('required')) <x-admin.required /> @endif</label>
    <div class="d-flex">
        <select name="{{ $attributes->get('name') }}" style="width: 100%" class="form-control" id="{{ $id }}"></select>
        @if(isset($create))
            <a class="btn btn-success d-inline ms-1 text-dark" onclick="{{$create}}">+</a>
        @endif
    </div>
    <script type="text/javascript">
        $(function () {
            let selector = $('#{{ $id }}').select2({
                placeholder: "{{ $attributes->get('placeholder', 'Please Select a Value') }}",
                allowClear: {{ $clear ? 1 : 0 }},
                ajax: {
                    url: '{{ $allRoute }}',
                    data: function (params) {
                        return {
                            filter: params.term,
                            __api_token: '{{ Auth::user()->getCurrentToken()->token }}',
                        };
                    },
                    type: 'post',
                }
            });
            @isset($_instance)
            selector.on('change', function (e) {
                let data = $('#{{ $id }}').select2("val");
                @this.set('{{ $attributes->get('name') }}', data);
            });
            @endisset
            @if($value !== null)
            $.ajax({
                url: '{{ $updateRoute }}',
                type: 'post', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', }
            }).then(function (data) {
                selector.append(new Option(data.text, data.id, true, true)).trigger('change');

                selector.trigger({
                    type: 'select2:select',
                    params: { data: data, }
                });
            });
            @endif
        });
    </script>
</div>

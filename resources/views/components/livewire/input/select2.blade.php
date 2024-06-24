@php
    $id = $attributes->get('id', Str::random());
    $route = $attributes->get('route');
    $allRoute = route('api.' . $route . '.select');
    $value = $attributes->get('value');
    $value = empty($value) ? null : $value;
    $clear = $attributes->get('clear', false);
    $updateRoute = null;
    if ($value !== null) {
        $updateRoute = route("api.{$route}.selected", ['id' => '%id%', ]);
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
<div wire:ignore style="padding-left: 5px;" class="form-group col-12 col-xl-{{ $attributes->get('width', 12) }}">
    <label for="{{ $id }}">
        {{ $attributes->get('label') }} @if($attributes->has('required')) <x-admin.required /> @endif
        @error($attributes->get('name')) <span class="text-danger">({{ $message }})</span> @enderror
    </label>
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
                @this.inputChanged('{{$attributes->get('name')}}');
            });
            @endisset
            window.addEventListener('updateValue', function (event) {
                if (event.detail.key === '{{ $attributes->get('name') }}') {
                    $.ajax({
                        url: '{{ $updateRoute }}'.replace('%id%', event.detail.value),
                        type: 'post', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', }
                    }).then(function (data) {
                        selector.append(new Option(data.text, data.id, true, true)).trigger('change');

                        selector.trigger({
                            type: 'select2:select',
                            params: { data: data, }
                        });
                    });
                }
            });
            @if(isset($value))
                dispatchUpdateEvent('{{ $attributes->get('name') }}', {{$value}})
            @endif
        });
    </script>
</div>

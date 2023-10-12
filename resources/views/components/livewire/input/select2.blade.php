@php
    $id = $attributes->get('id', Str::random());
    $route = $attributes->get('route');
    $allRoute = route('api.' . $route . '.select');
    $value = $attributes->get('value');
    $value = empty($value) ? null : $value;
    $updateRoute = null;
    if ($value !== null) {
        $updateRoute = route("api.{$route}.selected", ['id' => $value, ]);
    }
@endphp
<div wire:ignore class="form-group col-12 col-xl-{{ $attributes->get('width', 12) }}" style="padding-left: 5px">
    <label for="{{ $id }}">{{ $attributes->get('label') }} @if($attributes->has('required')) <x-admin.required /> @endif</label>
    <select style="width: 100%" class="form-control" id="{{ $id }}"></select>
    <script type="text/javascript">
        $(function () {
            let selector = $('#{{ $id }}').select2({
                placeholder: "Please Select a Value",
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
            selector.on('change', function (e) {
                let data = $('#{{ $id }}').select2("val");
                @this.set('{{ $attributes->get('name') }}', data);
            });
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

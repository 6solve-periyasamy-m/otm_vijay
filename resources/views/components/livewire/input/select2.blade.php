@php
    $id = $attributes->get('id', Str::random());
@endphp
<div wire:ignore class="form-group col-12 col-xl-{{ $attributes->get('width', 12) }}">
    <label for="{{ $id }}">{{ $attributes->get('label') }}</label>
    <select style="width: 100%" class="form-control" id="{{ $id }}"></select>
</div>
@push('footer-stack')
<script type="text/javascript">
    $(function () {
        let selector = $('#{{ $id }}').select2({
            placeholder: "Please Select a Value",
            ajax: {
                url: '{{ route('api.' . $attributes->get('route') . '.select') }}',
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
        @if($attributes->has('value'))
        $.ajax({
            url: '{{ route('api.' . $attributes->get('route') . '.selected', ['id' => $attributes->get('value', 0), ]) }}',
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
@endpush

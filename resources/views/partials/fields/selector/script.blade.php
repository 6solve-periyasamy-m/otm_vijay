let {{ $field }}Select = $('.{{ $field }}-input');
{{ $field }}Select.select2({
    ajax: {
        url: '{{ $fullRoute ?? route('api.' . $route . '.select') }}',
        data: function (params) {
            return {
                filter: params.term,
                __api_token: '{{ Auth::user()->getCurrentToken()->token }}',
                {{ $additionalParams }}
            };
        },
        type: 'post',
}
});
@if(!isset($preselect) || $preselect)
$.ajax({
    url: '{{ route('api.' . $route . '.selected', ['id' => old($field) ?? $id ?? 0, ]) }}',
    type: 'post', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', }
}).then(function (data) {
    {{ $field }}Select.append(new Option(data.text, data.id, true, true)).trigger('change');

    {{ $field }}Select.trigger({
        type: 'select2:select',
        params: { data: data, }
    });
});
@endif

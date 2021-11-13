let {{ $field }}Select = $('.{{ $field }}-input');
{{ $field }}Select.select2({
    ajax: {
        url: '{{ route('api.' . $route . '.select') }}',
        data: function (params) {
            return {
                filter: params.term,
                {{ $additionalParams }}
            };
        }
}
});
$.ajax({
    url: '{{ route('api.' . $route . '.selected', ['id' => $id ?? 0, ]) }}',
}).then(function (data) {
    {{ $field }}Select.append(new Option(data.text, data.id, true, true)).trigger('change');

    {{ $field }}Select.trigger({
        type: 'select2:select',
        params: { data: data, }
    });
});

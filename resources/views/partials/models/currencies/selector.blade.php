@push('head-stack')
<script type="text/javascript">
    $(document).ready(function () {
        let currencySelect = $('#currency_id-input');
        currencySelect.select2({
            ajax: {
                url: '{{ route('api.currencies.select') }}',
                data: function (params) {
                    return {filter: params.term,};
                }
            }
        });
        $.ajax({url: '{{ route('api.currencies.selected', ['id' => $id ?? 0, ]) }}',})
            .then(function (data) {
                currencySelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                currencySelect.trigger({
                    type: 'select2:select',
                    params: {data: data,}
                });
            });
    });
</script>
@endpush
<div class="form-group col-12">
    <label for="currency_id-input">Currency</label>
    <select name="currency_id" class="form-control" id="currency_id-input"></select>
</div>

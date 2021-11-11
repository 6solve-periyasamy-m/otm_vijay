@push('head-stack')
<script type="text/javascript">
    $(document).ready(function () {
        let {{ $prefix ?? "" }}addressSelect = $('#{{ $prefix ?? "" }}address_id-input');
        {{ $prefix ?? "" }}addressSelect.select2({
            ajax: {
                url: '{{ route('api.addresses.select') }}',
                data: function (params) {
                    return {filter: params.term, customers: {{ isset($customers) && $customers ? "true" : "false" }},};
                }
            }
        });
        $.ajax({url: '{{ route('api.addresses.selected', ['id' => $id ?? 0, ]) }}',})
            .then(function (data) {
                {{ $prefix ?? "" }}addressSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                {{ $prefix ?? "" }}addressSelect.trigger({
                    type: 'select2:select',
                    params: {data: data,}
                });
            });
    });
</script>
@endpush
<div class="form-group col-12">
    <label for="{{ $prefix ?? "" }}address-input">Address</label>
    <select name="{{ $prefix ?? "" }}address_id" class="form-control" id="{{ $prefix ?? "" }}address_id-input"></select>
</div>

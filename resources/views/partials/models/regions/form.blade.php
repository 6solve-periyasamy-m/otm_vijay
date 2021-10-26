@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let countrySelect = $('#country_id-input');
            countrySelect.select2({
                ajax: {
                    url: '{{ route('api.countries.select') }}',
                    data: function (params) {
                        return {filter: params.term,};
                    }
                }
            });
            $.ajax({url: '{{ route('api.countries.selected', ['id' => $country_id ?? 0, ]) }}',})
                .then(function (data) {
                    countrySelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    countrySelect.trigger({
                        type: 'select2:select',
                        params: {data: data,}
                    });
                });
        });
    </script>
@endsection
<form action="{{ $action }}" method="post">
    @csrf
    <div class="form-group">
        <label for="country_id-input">Country</label>
        <select name="country_id" class="form-control" id="country_id-input"></select>
        <a href="{{ route('countries.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
    </div>
    <p></p>
    <div class="form-group">
        <label for="name-input">Name</label>
        <input name="name" value="{{ $name ?? "" }}" class="form-control" id="name-input">
    </div>
    <p></p>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

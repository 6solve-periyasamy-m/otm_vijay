@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let countrySelect = $('#country_id-input');
            countrySelect.select2({
                ajax: {
                    url: '{{ route('api.countries.select') }}',
                    data: function (params) {
                        return {filter: params.term, __api_token: '{{ Auth::user()->getCurrentToken()->token }}',};
                    }
                }
            });
            $.ajax({url: '{{ route('api.countries.selected', ['id' => $country_id ?? 0, ]) }}', type: 'post', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', }})
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
<div class="card">
    <div class="card-body">
        <form action="{{ $action }}" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-12">
                    <label for="country_id-input">Country</label>
                    <div class="d-flex">
                        <select name="country_id" class="form-control" id="country_id-input"></select>
                        <a href="{{ route('countries.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
                    </div>
                </div>            
                <div class="form-group col-12">
                    <label for="name-input">Name</label>
                    <input name="name" value="{{ $name ?? "" }}" class="form-control" id="name-input">
                </div>                
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

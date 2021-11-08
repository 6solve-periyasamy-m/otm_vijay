@section('head-script')
<script type="text/javascript">
    $(document).ready(function() {
        let regionSelect = $('#region_id-input');
        regionSelect.select2({
           ajax: {
               url: '{{ route('api.regions.select') }}',
               data: function (params) { return {filter: params.term, __api_token: '{{ Auth::user()->getCurrentToken()->token }}',}; }
           }
        });
        $.ajax({ url: '{{ route('api.regions.selected', ['id' => $region_id ?? 0, ]) }}', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', } })
            .then(function (data) {
            regionSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

            regionSelect.trigger({
                type: 'select2:select',
                params: { data: data, }
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
                    <label for="region_id-input">Region</label>          
                    <div class="d-flex">
                        <select class="form-control" id="region_id-input" name="region_id"></select>
                        <a href="{{ route('regions.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
                    </div>
                </div>                
                <div class="form-group col-12">
                    <label for="name-input">Name</label>
                    <input name="name" value="{{ $name ?? "" }}" class="form-control" id="name-input">
                </div>                
                <div class="form-group col-12">
                    <label for="description-input">Description</label>
                    <input name="description" value="{{ $description ?? "" }}" class="form-control" id="description-input">
                </div>                
                <div class="form-group col-12">
                    <label for="audit_date-input">Audit Date</label>
                    <input type="date" name="audit_date" value="{{ $audit_date ?? "" }}" class="form-control" id="audit_date-input">
                </div>                
                <div class="form-group col-12">
                    <label for="address-input">Address</label>
                    <input name="address" value="{{ $address ?? "" }}" class="form-control" id="address-input">
                </div>                
                <div class="form-group col-12">
                    <label for="currency-input">Currency</label>
                    <input name="currency" value="{{ $currency ?? "" }}" class="form-control" id="currency-input">
                </div>                
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

@section('head-script')
<script type="text/javascript">
    $(document).ready(function() {
        let regionSelect = $('#region_id-input');
        regionSelect.select2({
           ajax: {
               url: '{{ route('api.regions.select') }}',
               data: function (params) { return {filter: params.term,}; }
           }
        });
        $.ajax({ url: '{{ route('api.regions.selected', ['id' => $region_id ?? 0, ]) }}', })
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
<form action="{{ $action }}" method="post">
    @csrf
    <div class="form-group">
        <label for="region_id-input">Region</label>
        <select class="form-control" id="region_id-input" name="region_id" style="width: 100%"></select>
    </div>
    <p></p>
    <div class="form-group">
        <label for="title-input">Title</label>
        <input name="title" value="{{ $title ?? "" }}" class="form-control" id="title-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="description-input">Description</label>
        <input name="description" value="{{ $description ?? "" }}" class="form-control" id="description-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="audit_date-input">Audit Date</label>
        <input type="date" name="audit_date" value="{{ $audit_date ?? "" }}" class="form-control" id="audit_date-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="address-input">Address</label>
        <input name="address" value="{{ $address ?? "" }}" class="form-control" id="address-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="currency-input">Currency</label>
        <input name="currency" value="{{ $currency ?? "" }}" class="form-control" id="currency-input">
    </div>
    <p></p>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

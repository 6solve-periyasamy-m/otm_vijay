@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let inventorySelect = $('#activity_inventory_id-input');
            inventorySelect.select2({
                ajax: {
                    url: '{{ route('api.inventory.activity.select') }}',
                    data: function (params) { return {filter: params.term,}; }
                }
            });
            $.ajax({ url: '{{ route('api.inventory.activity.selected', ['id' => $activity_inventory_id ?? 0, ]) }}', })
                .then(function (data) {
                    inventorySelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    inventorySelect.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
        });
    </script>
@endsection
<form action="{{ $action }}" method="post">
    @csrf
    <div id="form-group">
        <label for="activity_inventory_id-input">Activity Inventory</label>
        <select name="activity_inventory_id" class="form-control" id="activity_inventory_id-input"></select>
    </div>
    <p></p>
    <div id="form-group">
        <label for="tour_component_type-input">Tour Component Type</label>
        <select class="form-select accommodation-component-type-select" name="tour_component_type" id="tour_component_type-input">
            <option value="Included" selected>Included</option>
            <option value="Upgrade">Upgrade</option>
            <option value="Add-on">Add-on</option>
        </select>
    </div>
    <p></p>
    <div id="form-group">
        <label for="tour_sales_price-input">Tour Sales Price</label>
        <input name="tour_sales_price" value="{{ $tour_sales_price ?? "" }}" class="form-control"
               id="tour_sales_price-input">
    </div>
    <p></p>
    <div id="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

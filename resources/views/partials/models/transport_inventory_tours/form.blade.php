@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let inventorySelect = $('#transport_inventory_id-input');
            inventorySelect.select2({
                ajax: {
                    url: '{{ route('api.inventory.transport.select') }}',
                    data: function (params) { return {filter: params.term,}; }
                }
            });
            $.ajax({ url: '{{ route('api.inventory.transport.selected', ['id' => $transport_inventory_id ?? 0, ]) }}', })
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
<div class="card">
    <div class="card-body">
        <form action="{{ $action }}" method="post">
            @csrf
            <div class="row">
                <div class="form-group col-12">
                    <label for="transport_inventory_id-input">Transport Inventory</label>
                    <select style="width: 100%" name="transport_inventory_id" class="form-control" id="transport_inventory_id-input"></select>
                </div>
                <div class="form-group col-12">
                    <label for="tour_component_type-input">Tour Component Type</label>
                    <select class="form-select accommodation-component-type-select" name="tour_component_type" id="tour_component_type-input">
                        <option value="Included" selected>Included</option>
                        <option value="Upgrade">Upgrade</option>
                        <option value="Add-on">Add-on</option>
                    </select>
                </div>
                <div class="form-group col-12">
                    <label for="tour_sales_price-input">Tour Sales Price</label>
                    <input name="tour_sales_price" value="{{ $tour_sales_price ?? "" }}" class="form-control"
                        id="tour_sales_price-input">
                </div>
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

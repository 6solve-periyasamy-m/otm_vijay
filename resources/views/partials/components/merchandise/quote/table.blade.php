@php /** @var \App\Models\Quote\Quote $quote */ @endphp
<script type="text/javascript">
    let merchandiseTable;
    $(document).ready(function () {
        merchandiseTable = $('.merchandise-inventory-table').DataTable({
            fixedHeader: true,
            select: { style: "multi+shift" },
        });
    });
    @can('update', \App\Models\Quote\Quote::class)
    function getSelectedMerchandiseInventory() {
        let ids = [];
        merchandiseTable.rows({ selected: true, }).every((rowIdx, tableLoop, rowLoop) => {
            let row = merchandiseTable.row(rowIdx);
            ids.push($(row.node()).attr('inventory_id'));
        });
        if (ids.length <= 0) return alert('No components are selected');
        $.ajax({
            type: "POST",
            url: "{{ route('api.quote.components.add', ['quote' => $quote, 'type' => 'merchandise']) }}",
            dataType: "json",
            statusCode: {
                200: function () { alert('Components added successfully'); location.reload(); },
                400: function () { alert('An incorrect component type has been provided'); },
                403: function () { alert('Authentication has expired. Please refresh the page'); }
            },
            data: { "type": "Included", "ids": ids, "__api_token": '{{ Auth::user()->getCurrentToken()->token }}', },
        });
    }
    @endcan
</script>
@can('update', \App\Models\Quote\Quote::class)
    <div class="d-flex justify-content-between mb-3">
        <div></div>
        <a href="javascript:getSelectedMerchandiseInventory()" class="btn btn-primary ms-3 text-white">
            {{ Icon::create() }}
            <span>Add Selected Rows</span>
        </a>
    </div>
@endcan
<table style="width: 100%;" class="table table-striped merchandise-inventory-table">
    <thead class="thead-dark">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Type</th>
            <th scope="col">Variant</th>
            <th scope="col">Size</th>
            <th scope="col">FIT Selectable</th>
            <th scope="col">Stock</th>
            <th scope="col">Purchase Price</th>
            <th scope="col">Sales Price</th>
            <th scope="col">Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach(\App\Repository\Model\Merchandise\MerchandiseInventoryRepository::getBetweenDates(now(), now(), $quote->repository) as $inventory)
            @continue($inventory->component === null)
            <tr inventory_id="{{ $inventory->id }}">
                <td>{{ $inventory->component?->name }}</td>
                <td>{{ $inventory->component?->type?->name }}</td>
                <td>{{ $inventory?->variant }}</td>
                <td>{{ $inventory?->size }}</td>
                <td>
                    <input type="checkbox" disabled @if($inventory->fit_selectable == 1) checked @endif>
                </td>
                <td>
                    {{$inventory->stock - $inventory->used_stock}}/{{ $inventory->stock }}<br/>
                                                                  ({{$inventory->used_stock}} Sold)
                </td>
                <td>{{ f_currency($inventory->purchase_price) }}</td>
                <td>{{ f_currency($inventory->sales_price) }}</td>
                <td>{{ $inventory->internal_notes }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

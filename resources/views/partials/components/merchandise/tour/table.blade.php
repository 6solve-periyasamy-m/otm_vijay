<script type="text/javascript">
    let merchandiseTable;
    $(document).ready(function () {
        merchandiseTable = $('.merchandise-inventory-table').DataTable({
            fixedHeader: true,
            select: {style: "multi+shift"},
        });
    });
    @can('create', \App\Models\Merchandise\Merchandise::class)
    function getSelectedMerchandise() {
        let ids = [];
        merchandiseTable.rows({selected: true,}).every((rowIdx, tableLoop, rowLoop) => {
            let row = merchandiseTable.row(rowIdx);
            ids.push($(row.node()).attr('inventory_id'));
        });
        if (ids.length <= 0) return alert('No components are selected');
        $.ajax({
            type: "POST",
            url: "{{ route('api.tour.merchandise.inventory.add', ['tour' => $tour,]) }}",
            dataType: "json",
            statusCode: {
                200: function () {
                    alert('Components added successfully');
                    location.reload();
                },
                400: function () {
                    alert('An incorrect component type has been provided');
                },
                403: function () {
                    alert('Authentication has expired. Please refresh the page');
                }
            },
            data: {
                "type": $(".merchandise-component-type-select").find(":selected").val(),
                "ids": ids,
                "__api_token": '{{ Auth::user()->getCurrentToken()->token }}',
            },
        });
    }
    @endcan
</script>
@can('create', \App\Models\Merchandise\Merchandise::class)
    @include('partials.components.add-bar', ['component' => 'merchandise', 'href' => 'javascript:getSelectedMerchandise()'])
@endcan
<table style="width: 100%;" class="table table-striped merchandise-inventory-table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">Icon</th>
        <th scope="col">Name</th>
        <th scope="col">Inventory Included</th>
        <th scope="col">Notes</th>
    </tr>
    </thead>
    <tbody>
    @foreach(\App\Models\Merchandise\Merchandise::all() as $merchandise)
        @php
            /** @var \App\Models\Merchandise\Merchandise $merchandise */
            $used = $merchandise->repository->getInventoryIncludedOnTourCount($tour);
            $available = $merchandise->inventory()->count();
        @endphp
        <tr inventory_id="{{ $merchandise->id }}">
            <td><img src="{{ $merchandise->asset }}" class="image tiny"/></td>
            <td>{{$merchandise->name }}</td>
            <td>{{ $used }}/{{ $available }} ({{ $available > 0 ? ($used/$available) * 100 : 100 }}%)</td>
            <td>{{ $merchandise->internal_notes }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

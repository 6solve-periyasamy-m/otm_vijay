@php
/**
 * @var \App\Repository\Storage\Report\AccommodationByNightReportStorage $storage
 */
$period = $storage->getPeriod();
@endphp
<table>
    <thead>
    <tr>
        <th scope="col">Hotel</th>
        <th scope="col">Room Type</th>
        <th scope="col">Board Type</th>
        <th scope="col">Category</th>
        @foreach($period as $date)
            <th scope="col">{{ f_date($date) }} <br />Sold</th>
            <th scope="col">{{ f_date($date) }} <br />Available</th>
            <th scope="col">{{ f_date($date) }} <br />Total</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
        @foreach($storage->getGrouped() as $key => $grouped)
            @php /** @var \App\Models\Accommodation\AccommodationInventory[] $inventories */ @endphp
            @foreach($grouped as $iKey => $inventories)
                @php $first = $inventories[array_key_first($inventories)]; @endphp
                <tr>
                    <th scope="col">{{ $first->accommodation->name }}</th>
                    <td>{{ $first->roomType->name }}</td>
                    <td>{{ $first->boardType->name }}</td>
                    <td>{{ $first->category?->name }}</td>
                    @foreach($period as $date)
                        @php
                            $found = null;
                            foreach($inventories as $inventory) {
                                if ($inventory->check_in->lte($date) && $inventory->check_out->gte($date)) {
                                    $found = $inventory;
                                    break;
                                }
                            }
                        @endphp
                        @if($found !== null)
                            <td>{{ $found->used_stock }}</td>
                            <td>{{ $found->available_stock }}</td>
                            <td>{{ $found->total_stock }}</td>
                        @else
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
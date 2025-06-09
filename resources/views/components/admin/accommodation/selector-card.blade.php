<td class="align-middle fs-5 td-width-20"><a href="{{ route('accommodations.view', ['accommodation' => $storage->accommodation]) }}" title="View Accommodation" target="_blank">{{ $storage->accommodation->name }}</a></td>
<td class="align-middle">{{ $storage->room->name }}</td>
<td class="align-middle">{{ $storage->room->maximum_occupancy }}</td>
<td class="align-middle">{{ $storage->board->name }}</td>
<td class="align-middle">{{ $storage->category?->name ?? 'None' }}</td>
<td class="align-middle td-width-20">
    <div class="period-block d-flex flex-wrap gap-2">
        @foreach($period as $date)
            @php $onNight = $storage->getInventoryOnNight($date) @endphp
            <span>
                @if($storage->getInventoryOnNight($date) !== null)
                    @if($onNight->repository->getAvailableStock() >= ($travellers ?? 1))
                        <h4 class="badge badge-pill badge-success">{{ f_date($date) }}: {{ $onNight->repository->getAvailableStock() }}</h4>
                    @else
                        <h4 class="badge badge-pill badge-danger">{{ f_date($date) }}: {{ $onNight->repository->getAvailableStock() }}</h4>
                    @endif
                @else
                    <h4 class="badge badge-pill badge-danger">{{ f_date($date) }}: {{ Icon::cross() }}</h4>
                @endif
            </span>
        @endforeach
    </div>
</td>

<td  class="align-middle text-center">
    @if(($quantity ?? null) === null)
        <span>
            @if($selected)
                <button title="Remove Accommodation" wire:click="select({{$storage->accommodation->id}}, {{$storage->room->id}}, {{ $storage->board->id }}, {{ $storage->category?->id ?? "null" }})" class="btn btn-sm mb-1 btn-outline-danger">
                    {{ Icon::minus() }}
                </button>
            @else
                <button title="Add Accommodation" wire:click="select({{$storage->accommodation->id}}, {{$storage->room->id}}, {{ $storage->board->id }}, {{ $storage->category?->id ?? "null" }})" class="btn btn-sm mb-1 btn-outline-success">
                    {{ Icon::plus() }}
                </button>
            @endif
        </span>
    @endif
    @if(($quantity ?? null) !== null)
        <div class="d-flex flex-column align-items-center">
            <div class="d-flex align-items-center">
                <button title="Remove Quantity" class="btn btn-sm me-2 btn-outline-danger">
                    {{ Icon::minus() }}
                </button>

                <input type="text"
                    disabled
                    value="{{ $quantity ?? 0 }}"
                    class="form-control text-center me-2"
                    style="width: 60px; padding: 0.25rem;" />

                <button title="Add Quantity"
                        wire:click="addQuantity({{ $storage->accommodation->id }}, {{ $storage->room->id }}, {{ $storage->board->id }}, {{ $storage->category?->id ?? 'null' }})"
                        class="btn btn-sm btn-outline-success">
                    {{ Icon::plus() }}
                </button>
            </div>
        </div>
    @endif
</td>
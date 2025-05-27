<td class="align-middle">
    @if($storage->accommodation->image_url !== null)
        <img class="rounded border border-primary border-2" style="width: 100%; height: 150px; object-fit: cover;" src="{{ asset($storage->accommodation->image_url) }}" alt="{{ $storage->accommodation->name }}"/>
    @endif
</td>
<td class="align-middle fs-4">{{ $storage->accommodation->name }}</td>
<td class="align-middle">{{ $storage->room->name }}</td>
<td class="align-middle">{{ $storage->room->maximum_occupancy }}</td>
<td class="align-middle">{{ $storage->board->name }}</td>
<td class="align-middle">{{ $storage->category?->name ?? 'None' }}</td>
<td class="align-middle td-width-15">    
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
</td>

<td  class="align-middle">
    <span><a href="{{ route('accommodations.view', ['accommodation' => $storage->accommodation]) }}" title="View Accommodation" target="_blank" class="btn btn-sm mb-1 btn-outline-info">
        {{ Icon::eye() }}
    </a></span>
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
            <label class="fw-bold mb-1">Quantity</label> <!-- Common centered label -->

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
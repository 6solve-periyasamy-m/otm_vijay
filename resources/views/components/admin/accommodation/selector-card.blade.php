<x-admin.section.card>
    <div class="row">
        @if($storage->accommodation->image_url !== null)
            <div class="col-12">
                <img style="width: 100%; height: 100px; object-fit: cover;" src="{{ asset($storage->accommodation->image_url) }}" alt="{{ $storage->accommodation->name }}"/>
            </div>
            <hr class="splitter" />
        @endif

        <div class="row">
            <div class="col-10">
                <h3 class="font-bold">
                    {{ $storage->accommodation->name }}
                </h3>
            </div>
            <div class="col-1">
                <a href="{{ route('accommodations.view', ['accommodation' => $storage->accommodation]) }}" target="_blank" class="btn btn-sm mb-1 btn-outline-info">
                    {{ Icon::eye() }}
                </a>
            </div>
            @if(($quantity ?? null) === null)
            <div class="col-1">
                @if($selected)
                    <button title="Remove Accommodation" wire:click="select({{$storage->accommodation->id}}, {{$storage->room->id}}, {{ $storage->board->id }}, {{ $storage->category?->id ?? "null" }})" class="btn btn-sm mb-1 btn-outline-danger">
                        {{ Icon::minus() }}
                    </button>
                @else
                    <button title="Add Accommodation" wire:click="select({{$storage->accommodation->id}}, {{$storage->room->id}}, {{ $storage->board->id }}, {{ $storage->category?->id ?? "null" }})" class="btn btn-sm mb-1 btn-outline-success">
                        {{ Icon::plus() }}
                    </button>
                @endif
            </div>
            @endif
        </div>
        @if(($quantity ?? null) !== null)
            <hr class="splitter" />

            <div class="row">
                <div class="col-2">
                    <button title="Remove Quantity" wire:click="removeQuantity({{$storage->accommodation->id}}, {{$storage->room->id}}, {{ $storage->board->id }}, {{ $storage->category?->id ?? "null" }})" class="btn btn-sm mb-1 btn-outline-danger">
                        {{ Icon::minus() }}
                    </button>
                </div>
                <div class="col-8">
                    <label>Quantity</label>
                    <input type="text" disabled value="{{ $quantity }}" />
                </div>
                <div class="col-2">
                    <button title="Add Quantity" wire:click="addQuantity({{$storage->accommodation->id}}, {{$storage->room->id}}, {{ $storage->board->id }}, {{ $storage->category?->id ?? "null" }})" class="btn btn-sm mb-1 btn-outline-success">
                        {{ Icon::plus() }}
                    </button>
                </div>
            </div>
        @endif
        <hr class="splitter" />

        <div class="col-6">
            <div class="block">
                <h4 class="font-bold">Room</h4>
            </div>
            <div class="block">
                {{ $storage->room->name }}
            </div>
        </div>

        <div class="col-6">
            <div class="block">
                <h4 class="font-bold">Size</h4>
            </div>
            <div class="block">
                {{ $storage->room->maximum_occupancy }}
            </div>
        </div>

        <hr class="splitter" />

        <div class="col-6">
            <div class="block">
                <h4 class="font-bold">Board</h4>
            </div>
            <div class="block">
                {{ $storage->board->name }}
            </div>
        </div>

        <div class="col-6">
            <div class="block">
                <h4 class="font-bold" title="Category">Category</h4>
            </div>
            <div class="block">
                {{ $storage->category?->name ?? 'None' }}
            </div>
        </div>

        <hr class="splitter" />

        @foreach($period as $date)
            @php $onNight = $storage->getInventoryOnNight($date) @endphp
            <div class="col-4">
                @if($storage->getInventoryOnNight($date) !== null)
                    @if($onNight->repository->getAvailableStock() >= ($travellers ?? 1))
                        <h4 class="badge badge-pill badge-success">{{ f_date($date) }}: {{ $onNight->repository->getAvailableStock() }}</h4>
                    @else
                        <h4 class="badge badge-pill badge-danger">{{ f_date($date) }}: {{ $onNight->repository->getAvailableStock() }}</h4>
                    @endif
                @else
                    <h4 class="badge badge-pill badge-danger">{{ f_date($date) }}: {{ Icon::cross() }}</h4>
                @endif
            </div>
        @endforeach
    </div>
</x-admin.section.card>

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
        </div>

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
            <div class="col-4">
                @if($storage->getInventoryOnNight($date) !== null)
                    <h4 class="badge badge-pill badge-success">{{ f_date($date) }}: {{ Icon::check() }}</h4>
                @else
                    <h4 class="badge badge-pill badge-danger">{{ f_date($date) }}: {{ Icon::cross() }}</h4>
                @endif
            </div>
        @endforeach
    </div>
</x-admin.section.card>

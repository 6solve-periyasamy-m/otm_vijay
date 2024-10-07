<div>
    @isset($route)
        <a href="{{ route($route, [$field => $id,]) }}" class="btn btn-outline-info btn-sm mb-1" title="View">
            {{ Icon::eye() }}
        </a>
    @endisset
    <a href="{{ route('tours.duplicate', ['tour' => $id]) }}" class="btn btn-outline-secondary btn-sm mb-1" title="Duplicate">
        {{ Icon::copy() }}
    </a>
    @if(isset($modal))
        <button title="Edit" wire:click="$emit('openModal', '{{ $modal }}', {'{{$field}}': {{$id}}, {{$parent ?? ''}}})" class="btn btn-outline-success btn-sm mb-1">
            {{ Icon::edit() }}
        </button>
    @elseif(isset($edit))
        <a title="Edit" href="{{ route($edit, [$field => $id,]) }}" class="btn btn-outline-success btn-sm mb-1">
            {{ Icon::edit() }}
        </a>
    @endif
    <button wire:click="delete({{ $id }})" class="btn btn-outline-danger btn-sm mb-1" title="Delete">
        {{ Icon::delete() }}
    </button>
</div>

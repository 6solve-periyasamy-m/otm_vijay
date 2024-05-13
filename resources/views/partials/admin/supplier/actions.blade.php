<div>
    <a href="{{ route($route, [$field => $id,]) }}" class="btn btn-outline-info btn-sm mb-1" title="View">
        {{ Icon::eye() }}
    </a>
    <button wire:click="$emit('openModal', '{{ $modal }}', {'{{$field}}': {{$id}}})" class="btn btn-outline-success btn-sm mb-1" title="Edit">
        {{ Icon::edit() }}
    </button>
    <button wire:click="delete({{ $id }})" class="btn btn-outline-danger btn-sm mb-1" title="Delete">
        {{ Icon::delete() }}
    </button>
</div>

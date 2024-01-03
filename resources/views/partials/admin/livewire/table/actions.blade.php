<div>
    @isset($route)
        <a href="{{ route($route, [$field => $id,]) }}" class="btn btn-outline-info btn-sm mb-1">
            {{ Icon::eye() }}
        </a>
    @endisset
    <button wire:click="$emit('openModal', '{{ $modal }}', {'{{$field}}': {{$id}}, {{$parent ?? ''}}})" class="btn btn-outline-success btn-sm mb-1">
        {{ Icon::edit() }}
    </button>
    <button wire:click="delete({{ $id }})" class="btn btn-outline-danger btn-sm mb-1">
        {{ Icon::delete() }}
    </button>
</div>

<div>
    @isset($route)
        <a href="{{ route($route, [$field => $id,]) }}" class="btn btn-outline-info btn-sm mb-1" title="View">
            {{ Icon::eye() }}
        </a>
    @endisset
    @if(isset($modal))
        <button title="Edit" wire:click="$emit('openModal', '{{ $modal }}', {'{{$field}}': {{$id}}, {{$parent ?? ''}}})" class="btn btn-outline-success btn-sm mb-1">
            {{ Icon::edit() }}
        </button>
    @elseif(isset($edit))
        <a title="Edit" href="{{ route($edit, [$field => $id,]) }}" class="btn btn-outline-success btn-sm mb-1">
            {{ Icon::edit() }}
        </a>
    @endif
    <button wire:click="archive({{ $id }})" class="btn btn-outline-danger btn-sm mb-1" title="Archive">
        {{ Icon::archive() }}
    </button>
</div>

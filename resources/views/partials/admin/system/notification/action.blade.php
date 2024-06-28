<div>
    <button wire:click="seen({{$id}})" class="btn btn-outline-info btn-sm mb-1" title="Mark Seen">
        {{ Icon::eye() }}
    </button>
    @if(empty($resolved))
        <button wire:click="resolve({{ $id }})" class="btn btn-outline-success btn-sm mb-1" title="Mark Resolved">
            {{ Icon::check() }}
        </button>
    @else
        <button wire:click="resolve({{ $id }})" class="btn btn-outline-danger btn-sm mb-1" title="Mark Unresolved">
            {{ Icon::cross() }}
        </button>
    @endif
</div>

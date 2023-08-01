<div>
    <button wire:click="invert({{ $id }})" class="btn btn-outline-{{ $invert ? 'success' : 'warning' }} btn-sm mb-1">
        {{ $invert ? Icon::solid('check') : Icon::solid('xmark') }}
    </button>
    <button wire:click="unlink({{ $tour }})" class="btn btn-outline-danger btn-sm mb-1">
        {{ Icon::delete() }}
    </button>
</div>

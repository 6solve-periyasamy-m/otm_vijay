<div class="btn-group">
    {{-- EDIT --}}
    <button type="button" class="btn btn-outline-success btn-sm mb-1" 
        wire:click="$emit('openModal','admin.event.content.form', { eventId: {{ $eventId }}, type: {{ $type }}, contentId: {{ $id }} } )" title="Edit">
        {{ Icon::edit() }}
    </button> &nbsp;&nbsp;
    {{-- DELETE --}}
    <button type="button" class="btn btn-outline-danger btn-sm mb-1" title="Delete" wire:click="delete({{ $id }})" >
        {{ Icon::delete() }}
    </button>
</div>
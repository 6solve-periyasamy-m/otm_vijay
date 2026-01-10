<div>
    <div class="d-flex justify-content-between mb-2">
        <h5>{{ $this->typeEnum->label() }}</h5>
        <button class="btn btn-primary" wire:click="add">
            Add {{ $this->typeEnum->label() }}
        </button>
    </div>
    <livewire:admin.event.content.table :eventId="$eventId" :type="$type"   :key="$type.'-'.$eventId"  />
</div>

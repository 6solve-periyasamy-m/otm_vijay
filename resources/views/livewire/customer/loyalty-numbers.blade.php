<div>
    
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Loyalty Numbers</h3>
        <button type="button" wire:click="add" class="btn btn-primary btn-sm">
            Add Loyalty Number
        </button>
    </div>

    @foreach($loyalties as $key => $loyalty)
        <div class="row align-items-end border rounded p-2 mb-2" wire:key="loyalty-{{ $key }}">
            {{-- <x-livewire.input
                name="loyalties.{{ $key }}.type"
                label="Type"
                value=""
                width="4"
            /> 
            <x-livewire.input
                wire:model="loyalties.{{ $key }}.notes"
                label="Details"
                width="3"
            />
            <x-livewire.input
                wire:model="loyalties.{{ $key }}.name"
                label="Loyalty Number"
                width="3"
            />
            --}}
            <div class="col-md-4">
                <label for="type_{{ $key }}" class="form-label">Type</label>
                <select id="type_{{ $key }}" wire:model="loyalties.{{ $key }}.type" class="form-control">
                    <option value="">Select Type</option>
                </select>
            </div>
            <x-livewire.input
                label="Details"
                width="3"
            />
            <x-livewire.input
                label="Loyalty Number"
                width="3"
            />
            <div class="col-2 text-center">
                <button type="button" wire:click="remove({{ $key }})" class="btn btn-outline-danger">
                    <i class="bi bi-trash"></i>Delete
                </button>
            </div>
        </div>
    @endforeach

    @if(session()->has('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <div class="mt-3">
        <button type="button" wire:click="save" class="btn btn-secondary">
            Update Loyalty Numbers
        </button>
    </div>
</div>

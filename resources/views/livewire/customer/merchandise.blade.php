<div>
    
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Merchandise</h3>
        <button type="button" wire:click="add" class="btn btn-primary btn-sm">
            Add Merchandise
        </button>
    </div>

    @foreach($merchandise as $key => $loyalty)
        <div class="row align-items-end border rounded p-2 mb-2" wire:key="loyalty-{{ $key }}">
            {{-- <x-livewire.input
                name="merchandise.{{ $key }}.category"
                label="Category"
                value=""
                width="4"
            /> 
            <x-livewire.input
                wire:model="merchandise.{{ $key }}.size"
                label="Size"
                width="3"
            />
            <x-livewire.input
                wire:model="merchandise.{{ $key }}.order_details"
                label="Other Details"
                width="3"
            />
            --}}
            <div class="col-md-4">
                <label for="category_{{ $key }}" class="form-label">Category</label>
                <select id="category_{{ $key }}" wire:model="merchandise.{{ $key }}.type" class="form-control">
                    <option value="">Select category</option>
                </select>
            </div>
            <x-livewire.input
                label="Size"
                width="3"
            />
            <x-livewire.input
                label="Other Details"
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
            Update Merchandise
        </button>
    </div>
</div>

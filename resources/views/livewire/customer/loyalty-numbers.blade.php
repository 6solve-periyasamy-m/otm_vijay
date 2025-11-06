<div>
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Loyalty / Memberships</h3>
        <div wire:click="add" class="cursor-pointer d-flex align-items-center justify-content-center rounded p-1 icon-border" title="Add Loyalty Item" role="button" tabindex="0" aria-label="Add Loyalty Item">
            <img src="{{ asset('images/customer/images/add_btn_color.svg') }}"  class="reservation_wht_icn plus-icon-size"  alt="Add" />
        </div>
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
                <div wire:click="remove({{ $key }})" class="cursor-pointer d-flex align-items-center justify-content-center rounded p-1 icon-no-border" title="Remove Merchandise Item" role="button" tabindex="0" aria-label="Remove Merchandise Item">
                    <img src="{{ asset('images/customer/images/remove.png') }}"  class="reservation_wht_icn plus-icon-size"  alt="Remove" />
                </div>
            </div>
        </div>
    @endforeach

    @if(session()->has('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <div class="mt-3">
        <div wire:click="save" class="btn d-inline-flex align-items-center gap-2 btn-bg-border" role="button" tabindex="0" title="Update Loyalties" aria-label="Update Loyalties" style="border:1px solid #F35B15; border-color: #F35B15; height: 32px; background-color: #F9F4EE;">
            <img  src="{{ asset('images/customer/images/merchandise.png') }}"   class="reservation_wht_icn"   alt="Update Loyalties" />
            <span>Update Loyalties</span>
        </div>
    </div>
</div>

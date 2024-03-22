<x-admin.section.card>
    <x-slot:title>
        Create Brand
    </x-slot:title>
    <div class="row">
        <div class="form-group col-12 col-xl-3" style="padding-left: 5px;">
            <label>Brand Name</label>
            <input type="text" class="form-control" wire:model="brand.name">
        </div>
        <div class="form-group col-12 col-xl-3" style="padding-left: 5px;">
            <label>Brand Email</label>
            <input type="text" class="form-control" wire:model="brand.email">
        </div>
        <div class="form-group col-12 col-xl-3" style="padding-left: 5px;">
            <label>Brand Phone</label>
            <input type="text" class="form-control" wire:model="brand.phone">
        </div>
        <div class="form-group col-12 col-xl-3" style="padding-left: 5px;">
            <label>Brand Logo</label>
            <input type="file" class="form-control" wire:model="logo">
        </div>
        <div class="form-group col-12 col-xl-3" style="padding-left: 5px;">
            <label>Brand URL</label>
            <input type="text" class="form-control" wire:model="brand.url">
        </div>
        <div class="form-group col-12 col-xl-3" style="padding-left: 5px;">
            <label>Brand Facebook</label>
            <input type="text" class="form-control" wire:model="brand.facebook">
        </div>
        <div class="form-group col-12 col-xl-3" style="padding-left: 5px;">
            <label>Brand Twitter</label>
            <input type="text" class="form-control" wire:model="brand.twitter">
        </div>
        <div class="form-group col-12 col-xl-3" style="padding-left: 5px;">
            <label>Brand Instagram</label>
            <input type="text" class="form-control" wire:model="brand.instagram">
        </div>
        <hr class="splitter"/>
        <div class="form-group col-12" style="padding-left: 5px;">
            <input type="checkbox" class="form-check-input" wire:model="useSystemAddress">
            <label>Use System Address</label>
        </div>
        <hr class="splitter"/>
        @if(!$this->useSystemAddress)
            <div class="form-group col-12 col-xl-6" style="padding-left: 5px;">
                <label>Address Line 1</label>
                <input type="text" class="form-control" wire:model="address.address_line_1">
            </div>
            <div class="form-group col-12 col-xl-6" style="padding-left: 5px;">
                <label>Address Line 2</label>
                <input type="text" class="form-control" wire:model="address.address_line_2">
            </div>
            <div class="form-group col-12 col-xl-6" style="padding-left: 5px;">
                <label>Town</label>
                <input type="text" class="form-control" wire:model="address.town">
            </div>
            <div class="form-group col-12 col-xl-6" style="padding-left: 5px;">
                <label>Region</label>
                <input type="text" class="form-control" wire:model="address.region">
            </div>
            <div class="form-group col-12 col-xl-6">
                <label>Country</label>
                <input type="text" class="form-control" wire:model="country">
            </div>
            <div class="form-group col-12 col-xl-6" style="padding-left: 5px;">
                <label>Postcode</label>
                <input type="text" class="form-control" wire:model="address.postcode">
            </div>
            <hr class="splitter"/>
        @endif
        <div class="form-group col-12 col-xl-12">
            <label></label>
            <button wire:click="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</x-admin.section.card>

<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h4 class="fw-bold">Create Brand</h4>
        </div>
        <div class="row">
            <div class="form-group col-12 col-xl-4" style="padding-left: 5px;">
                <label>Brand Name</label>
                <input type="text" class="form-control" wire:model="brand.name">
            </div>
            <div class="form-group col-12 col-xl-4" style="padding-left: 5px;">
                <label>Brand Email</label>
                <input type="text" class="form-control" wire:model="brand.email">
            </div>
            <div class="form-group col-12 col-xl-4" style="padding-left: 5px;">
                <label>Brand Phone</label>
                <input type="text" class="form-control" wire:model="brand.phone">
            </div>
            <div class="form-group col-12 col-xl-3" style="padding-left: 5px;">
                <label>Brand URL</label>
                <input type="text" class="form-control" wire:model="brand.url">
            </div>
            <div class="form-group col-12 col-xl-3" style="padding-left: 5px;">
                <label>Brand Facebook</label>
                <input type="text" class="form-control" wire:model="brand.url">
            </div>
            <div class="form-group col-12 col-xl-3" style="padding-left: 5px;">
                <label>Brand Twitter</label>
                <input type="text" class="form-control" wire:model="brand.url">
            </div>
            <div class="form-group col-12 col-xl-3" style="padding-left: 5px;">
                <label>Brand Instagram</label>
                <input type="text" class="form-control" wire:model="brand.url">
            </div>
            <div class="form-group col-12 col-xl-12">
                <label></label>
                <button wire:click="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>

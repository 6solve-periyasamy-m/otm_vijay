<div class="card">
    <div class="card-body">
        <div class="row">
            <x-livewire.input append="%" label="Amount" wire:model="amount" width="10" />
            <div class="col-xl-2 my-auto">
                <button class="btn btn-primary" wire:click="submit">
                    Submit
                </button>
            </div>
        </div>
    </div>
</div>

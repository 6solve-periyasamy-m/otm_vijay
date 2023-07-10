<x-customer.accordion id="voucher-collapse" nobg>
    <x-slot:header>
        <h2 class="mb-0" style="width: 100%; text-align: center;">Apply Voucher</h2>
    </x-slot:header>

    <div class="row">
        <x-customer.input wire:model="code" width="10">
            Voucher Code
        </x-customer.input>
        <div class="col-xl-2">
            <button class="btn btn-success" wire:click="apply">
                Apply Voucher
            </button>
        </div>
    </div>

</x-customer.accordion>
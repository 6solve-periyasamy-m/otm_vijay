<x-admin.section.card>
    <x-slot:title>
        {{ __('voucher.tour.card.title') }}
    </x-slot:title>
    <div class="row mb-2">
        <div class="col-8">
            <x-livewire.input.select2 name="tour" label="Tour" route="tours" />
        </div>
        <div class="col-2 my-auto">
            <button class="btn btn-success" wire:click="include">
                {{ Icon::create() }} {{ __('voucher.tour.card.include') }}
            </button>
        </div>
        <div class="col-2 my-auto">
            <button class="btn btn-warning" wire:click="exclude">
                {{ Icon::create() }} {{ __('voucher.tour.card.exclude') }}
            </button>
        </div>
    </div>
    <livewire:admin.voucher.tour.table :voucher="$voucher->id" />
</x-admin.section.card>

<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h4 class="fw-bold">{{ __('voucher.tour.card.title') }}</h4>
        </div>
        <div class="row mb-2">
            <div class="col-10">
                <x-livewire.input.select2 name="tour" label="Tour" route="tours" />
            </div>
            <div class="col-2 my-auto">
                <button class="btn btn-primary" wire:click="create">
                    {{ Icon::create() }} {{ __('voucher.tour.card.include') }}
                </button>
            </div>
        </div>
        {{ $tour }}
    </div>
</div>

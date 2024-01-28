<x-admin.section.card>
    <x-slot:title>
        {{ __('voucher.result.card.title') }}
    </x-slot:title>
    <div class="row mb-2">
        <div class="col-10">
            <select class="form-select" wire:model="executor">
                @foreach(\App\Models\Voucher\ResultType::cases() as $type)
                    @if($type === \App\Models\Voucher\ResultType::FREE_COMPONENT) @continue @endif
                    <option value="{{ $type->value }}">{{ $type->description() }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-2">
            <button class="btn btn-primary" wire:click="create">
                {{ Icon::create() }} {{ __('voucher.result.card.create') }}
            </button>
        </div>
    </div>
    <livewire:admin.voucher.result.table :voucher="$voucher->id" />
</x-admin.section.card>

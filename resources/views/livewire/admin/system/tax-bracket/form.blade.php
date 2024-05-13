<x-admin.section.card>
    <x-slot:title>{{ __('custom.tax.form.title.' . ($bracket->id === null ? 'create' : 'update')) }}</x-slot:title>
    <div class="row">
        <x-livewire.input width="2" wire:model="bracket.name" label="{{__('custom.tax.form.fields.name')}}" />
        <x-livewire.input width="6" wire:model="bracket.description" label="{{__('custom.tax.form.fields.description')}}" />
        <x-livewire.input width="2" wire:model="bracket.rate" label="{{__('custom.tax.form.fields.rate')}}" />
        <div class="col-xl-2">
            <button class="btn btn-success" wire:click="save">
                Save
            </button>
        </div>
    </div>
</x-admin.section.card>
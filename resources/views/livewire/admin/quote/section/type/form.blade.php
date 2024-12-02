<x-admin.section.card>
    <div class="row">
        <x-livewire.input wire:model="type.name" label="Name" width="5" />
        <x-livewire.input.select.large-text-template name="type.large_text_template_id" vale="{{ $type->large_text_template_id }}" label="Template" width="5" />
        <div class="col-xl-2">
            <button wire:click="save" class="btn btn-primary">
                Save
            </button>
        </div>
    </div>
</x-admin.section.card>

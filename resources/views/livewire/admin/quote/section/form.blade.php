<div>
    <x-admin.section.card>
        <div class="float-end">
            <button wire:click="save" class="btn btn-success">{{ Icon::save() }} Save Section</button>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <div class="row">
            <x-livewire.input wire:model="section.title" label="Name" />
            <x-livewire.input.select.large-text-template name="bodyTemplate" label="Copy from Template" value="{{ $bodyTemplate }}" />
            <x-livewire.ckeditor name="section.body" value="{{ $section?->body }}" label="Body" />
            <x-livewire.input type="file" width="4" wire:model="image" label="Image" />
            <x-livewire.input type="number" width="4" wire:model="section.order" label="Order" />
            <x-livewire.input.checkbox wire:model="section.hidden" label="Hide on document?" width="4" />
        </div>
    </x-admin.section.card>
</div>

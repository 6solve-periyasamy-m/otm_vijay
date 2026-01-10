<x-admin.section.card>
    <x-slot:title>
        {{ $content->exists ? 'Update' : 'Create' }}
        {{ $content->type?->label() }}
    </x-slot:title>

    <div class="row">
        <x-livewire.input wire:model.defer="content.question" required width="6" label="Question" />
        <x-livewire.input.checkbox wire:model="content.active" width="3" label="Active" />
        <x-livewire.ckeditor name="content.answer" value="{{ $content->answer }}" label="Answer" />
        <div class="col-xl-2"><p class="margin: 20px 0px;"> <button class="btn btn-success" wire:click="save"> Save </button> </p></div>
    </div>
</x-admin.section.card>

@php $types = \App\Models\Helper\Enum\LargeTextType::toArray(); @endphp
<x-admin.section.card>
    <div class="row">
        <x-livewire.input wire:model="template.name" width="8" label="Name" />
        <x-livewire.input.dropdown wire:model="template.type" width="4" :items="$types" label="Template Type"/>
        <x-livewire.input wire:model="template.description" label="Description" />
        <x-livewire.ckeditor name="template.content" value="{{ $template->content }}" label="Content" />
        <div class="col-12">
            <button class="btn btn-success" wire:click="save">
                Submit
            </button>
        </div>
    </div>
</x-admin.section.card>
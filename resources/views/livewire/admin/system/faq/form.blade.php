<x-admin.section.card>
    <x-slot:title>{{ __( ($faq->id === null ? 'Create' : 'Update')) . ' FAQ' }}</x-slot:title>
    <div class="row">
        <x-livewire.input wire:model="faq.question" required width="6" label="Question" />
        <x-livewire.input.select.brand name="faq.brand_id" value="{{$faq->brand_id}}" label="Branding" width="3" />
        <x-livewire.input.checkbox wire:model="faq.active" required width="3" label="Status" />
        <x-livewire.input.text-area wire:model="faq.answer" required label="Answer" />
        <div class="col-xl-2">
            <button class="btn btn-success" wire:click="save">
                Save
            </button>
        </div>
    </div>
</x-admin.section.card>
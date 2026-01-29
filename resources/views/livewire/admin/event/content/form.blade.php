<x-admin.section.card>
    <x-slot:title>
        {{ $content->exists ? 'Update' : 'Create' }}
        {{ $content->type?->label() }}
    </x-slot:title>
    <div class="row">
        <x-livewire.input wire:model.defer="content.question" required width="6" label="Question" />
        <x-livewire.input.checkbox wire:model="content.active" width="3" label="Active" />
        <div class="col-xl-3 d-flex align-items-center gap-2">
            <div class="flex-grow-1">
                <label class="form-label">Icon (SVG / JPG / PNG)</label>
                <input type="file" class="form-control" wire:model="icon">
            </div>
            <div>
                {{-- Preview new upload --}}
                @if ($content->exists && $content->icon)
                    <img src="{{ asset($content->icon) }}" alt="{{$content->question}}" class="img-fluid" style="max-width: 32px; max-height: 32px;"/>
                @endif
            </div>
        </div>
        <x-livewire.ckeditor name="content.answer" value="{{ $content->answer }}" label="Answer" />
        <div class="col-xl-2"><p class="margin: 20px 0px;"> <button class="btn btn-success" wire:click="save"> Save </button> </p></div>
    </div>
</x-admin.section.card>

<x-admin.section.card>
    <div class="float-end">
        <div wire:click="closeModal">
            {{ Icon::close() }}
        </div>
    </div>
    <div class="image xl">
        <img style="min-width: 50%; height:auto;" src="{{ asset($image) }}"  alt=""/>
    </div>
</x-admin.section.card>
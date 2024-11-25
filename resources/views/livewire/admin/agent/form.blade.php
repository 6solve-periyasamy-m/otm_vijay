<x-admin.section.card>
    <x-slot:title>
        {{ __($agent?->id !== null ? 'agent.form.title.update' : 'agent.form.title.create') }}
    </x-slot:title>
    <x-slot:title>
        {{ $organization ? 'Organization Agent' : 'Contact'}} Details
    </x-slot:title>
    <x-livewire.input tabindex="0" wire:model="agent.first_name" required width="40" label="{{__('agent.form.fields.first_name')}}" />
    <x-livewire.input tabindex="0" wire:model="agent.last_name" required width="40" label="{{__('agent.form.fields.last_name')}}" />
    <x-livewire.input tabindex="0" wire:model="agent.email" width="30" label="{{__('agent.form.fields.email')}}" />
    <button class="btn btn-primary" wire:click="save">Submit</button>
</x-admin.section.card>
<div class="row">
    <div class="col-12">
        <x-admin.section.card>
            <div class="flex justify-between">
                <div style="min-width: 25%">
                    <x-livewire.input.select.accommodation name="accommodation" value="{{$accommodation}}" label="Accommodation" />
                </div>
                <div style="min-width: 25%">
                    <x-livewire.input type="date" wire:model="start" label="Start Date" />
                </div>
                <div style="min-width: 25%">
                    <x-livewire.input type="date" wire:model="end" label="End Date" />
                </div>
                <div style="min-width: 25%">
                    <div class="flex justify-center" style="max-height: 100%">
                        <div class="my-auto">
                            <button wire:click="save" class="btn btn-success">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </x-admin.section.card>
    </div>
    <div class="row">
        @foreach($this->fetchData() as $data)
            <div class="col-3 @if($this->selected($data)) selected @endif" wire:click="select({{$data->accommodation->id}}, {{$data->room->id}}, {{ $data->board->id }}, {{ $data->category?->id ?? "null" }})">
                <x-admin.accommodation.accommodation-by-date :storage="$data" :start="$this->getStart()" :end="$this->getEnd()" />
            </div>
        @endforeach
    </div>
    <x-wire-loader/>
</div>

<div class="row">
    <div class="col-12">
        <x-admin.section.card>
            <div class="flex justify-between">
                <div style="min-width: 10%" class="mx-1">
                    <div class="flex" style="max-height: 100%">
                        <div class="my-auto">
                            <a class="btn btn-primary" href="{{ $this->getReturnUrl() }}">Return to {{ $this->getPackageType() }}</a>
                        </div>
                    </div>
                </div>
                <div style="min-width: 30%" class="mx-1">
                    <x-livewire.input type="date" wire:model="start" label="Start Date" />
                </div>
                <div style="min-width: 30%" class="mx-1">
                    <x-livewire.input type="date" wire:model="end" label="End Date" />
                </div>
                <div style="min-width: 30%" class="mx-1">
                    <div class="flex" style="max-height: 100%">
                        <div class="my-auto">
                            <label class="block text-danger">Warning: This will remove all accommodation from the {{ strtolower($this->getPackageType()) }}</label>
                            <button wire:click="save" class="btn btn-success">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </x-admin.section.card>
    </div>
    <div class="col-12">
        <x-admin.section.card>
            <div class="flex justify-between">
                <div style="min-width: 25%">
                    <x-livewire.input.select.accommodation clear name="accommodation" value="{{$accommodation}}" label="Accommodation" />
                </div>
                <div style="min-width: 25%">
                    <x-livewire.input.select.accommodation.room-type clear name="room" value="{{$room}}" label="Room Type" />
                </div>
                <div style="min-width: 25%">
                    <x-livewire.input.select.accommodation.board-type clear name="board" value="{{$board}}" label="Board Type" />
                </div>
                <div style="min-width: 25%">
                    <x-livewire.input.select.accommodation.room-category clear name="category" value="{{$category}}" label="Category" />
                </div>
            </div>
        </x-admin.section.card>
    </div>
    <div class="row">
        <table class="table " >
            <thead wire:ignore>
                <tr>
                    <th scope="col">&nbsp;</th>
                    <th scope="col"><strong>Name</strong></th>
                    <th scope="col"><strong>Room</strong></th>
                    <th scope="col"><strong>Size</strong></th>
                    <th scope="col"><strong>Board</strong></th>
                    <th scope="col"><strong>Category</strong></th>
                    <th scope="col"><strong>Stock Availability</strong></th>
                    <!-- <th scope="col"><strong>Quantity</strong></th> -->
                    <th scope="col"><strong>Action</strong></th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->fetchData() as $data)
                    @continue(!$this->shouldShow($data))
                    @php $selected = $this->selected($data); @endphp
                    <tr data-slt="{{ $selected }}" class="@if($selected) selected @endif">
                        <x-admin.accommodation.accommodation-selector-card :key="now()" :storage="$data" :start="$this->getStart()" :end="$this->getEnd()" :selected="$selected" :travellers="$travellers" :quantity="$this->getQuantity($data)"/>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <x-wire-loader/>
</div>

<div>
    <x-customer.accordion id="components" nobg nocontainer>
        <x-slot:header class="card-body">
            <h2 class="mb-0">Components for {{ $this->traveller->full_name }}</h2>
        </x-slot:header>
        @foreach($this->traveller->repository->getSummaryComponents() as $day => $components)
            <div class="mx-1">
                <x-customer.accordion id="day-{{$day}}" nobg nocontainer>
                    <x-slot:header class="card-body">
                        <h2 class="mb-0">{{ \Carbon\Carbon::createFromTimestamp($day)->format('l jS F Y') }}</h2>
                    </x-slot:header>
                    @foreach($components as $component)
                        <livewire:customer.booking.component-card key="{{now()}}" :component="$component"/>
                    @endforeach
                </x-customer.accordion>
            </div>
        @endforeach
    </x-customer.accordion>
    <x-wire-loader />
</div>

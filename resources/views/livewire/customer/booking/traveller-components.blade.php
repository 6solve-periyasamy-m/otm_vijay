<div>
    @foreach($this->traveller->repository->getSummaryComponents() as $day => $components)
        <x-customer.accordion id="day-{{$day}}" nobg nocontainer>
            <x-slot:header class="card-body">
                <h2 class="mb-0">{{ \Carbon\Carbon::createFromTimestamp($day)->format('l jS F Y') }}</h2>
            </x-slot:header>
            @foreach($components as $component)
                <livewire:customer.booking.component-card :component="$component"/>
            @endforeach
        </x-customer.accordion>
    @endforeach
</div>

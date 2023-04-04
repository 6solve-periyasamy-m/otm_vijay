<div>
    @foreach($this->traveller->repository->getSummaryComponents() as $component)
        <livewire:customer.booking.component-card :component="$component"/>
    @endforeach
</div>

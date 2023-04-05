<div>
    @foreach($this->traveller->repository->getSummaryComponents() as $day => $components)
        <div class="card">
          <div class="card-body">
             <div class="card-title">
                 {{ \Carbon\Carbon::createFromTimestamp($day)->format('l jS F Y') }}
             </div>
          </div>
        </div>
        @foreach($components as $component)
            <livewire:customer.booking.component-card :component="$component"/>
        @endforeach
    @endforeach
</div>

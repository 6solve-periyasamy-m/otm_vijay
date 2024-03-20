<x-admin.section.card>
    <div class="row">
        <div class="col-8">
            <span class="fw-bold">{{ $bracket->name }}</span><br/>
            {{ $bracket->description }}
        </div>
        <div class="col-2 my-auto">
            @if($bracket->rate === null)
                No Taxes
            @else
                {{ $bracket->rate }}%
            @endif
        </div>
        <div class="col-2 my-auto">
            @if(setting('system.tax.bracket') === $bracket->id)
                {{ Icon::check() }}
            @else
                {{ Icon::cross() }}
            @endif
        </div>
    </div>
</x-admin.section.card>
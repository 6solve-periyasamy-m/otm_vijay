<x-admin.section.card>
    <div class="row clickable" wire:click="updateTaxBracket({{ $bracket->id }})">
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
            @if((setting('system.tax.bracket')) == $bracket->id)
                {{ Icon::check() }}
            @else
                {{ Icon::cross() }}
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            @if($bracket->id === null)
                <span class="btn btn-outline-dark">
                    {{ Icon::edit() }} Edit
                </span>
            @else
                <button class="btn btn-outline-success" onclick="openModal('admin.system.tax-bracket.form', {'bracket': {{$bracket->id}},});">
                    {{ Icon::edit() }} Edit
                </button>
            @endif
        </div>
        <div class="col-6">
            @if($bracket->id === null)
                <span class="btn btn-outline-dark">
                    {{ Icon::delete() }} Delete
                </span>
            @else
                <button class="btn btn-outline-danger" wire:click="delete({{$bracket->id}})">
                    {{ Icon::delete() }} Delete
                </button>
            @endif
        </div>
    </div>
</x-admin.section.card>

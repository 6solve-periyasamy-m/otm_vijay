@php
    /** @var App\Models\Tour\Tour $tour */
@endphp 
<div class="modal fade common-modal-custom" id="optionTour" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-auto-width modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="panel-grid panel-grid-3-5 custom-panel p-0 relative bg-transparent">
                    <button type="button" class="absolute right-0 top-0" data-bs-dismiss="modal" aria-label="Close">
                    {{ Icon::xmark() }}
                    </button>
                    @can('update', \App\Models\Merchandise\Merchandise::class)
                    <x-admin.popup-button href="{{ route('tours.fulfil', ['tour' => $tour,]) }}" class="color-danger row-1">
                        <x-slot:icon>{{ Icon::redo() }}</x-slot:icon>
                        Fulfil Merchandise
                    </x-admin.popup-button>
                    @endcan
                    @if($tour->protected && $tour->has_atol_certificate)
                    <x-admin.popup-button href="{{ route('tours.atol', ['tour' => $tour,]) }}" class="color-info row-1">
                        <x-slot:icon>{{ Icon::atol() }}</x-slot:icon>
                        Export ATOL Certificates
                    </x-admin.popup-button>
                    @endif
                    @can('costing', \App\Models\Tour\Tour::class)
                    <x-admin.popup-button href="{{ route('tours.costing', ['tour' => $tour,]) }}" class="color-warning row-2">
                        <x-slot:icon>{{ Icon::chart() }}</x-slot:icon>
                        View Costing
                    </x-admin.popup-button>
                    @endcan
                    <x-admin.popup-button href="{{ route('tours.rooming', ['tour' => $tour,]) }}" class="color-secondary row-3">
                        <x-slot:icon>{{ Icon::list() }}</x-slot:icon>
                        View Rooming
                    </x-admin.popup-button>
                    <x-admin.popup-button href="{{ route('tours.rooming', ['tour' => $tour, 'notes' => false,]) }}" class="color-secondary row-3">
                        <x-slot:icon>{{ Icon::list() }}</x-slot:icon>
                        View Rooming (No Notes)
                    </x-admin.popup-button>
                    <x-admin.popup-button href="{{ route('tours.manifest.activity.view', ['tour' => $tour,]) }}" class="color-secondary row-3">
                        <x-slot:icon>{{ Icon::list() }}</x-slot:icon>
                        View Activity Manifest
                    </x-admin.popup-button>
                    <x-admin.popup-button href="{{ route('tours.manifest.flight.view', ['tour' => $tour,]) }}" class="color-secondary row-3">
                        <x-slot:icon>{{ Icon::list() }}</x-slot:icon>
                        View Flight Manifest
                    </x-admin.popup-button>
                    <x-admin.popup-button href="{{ route('tours.manifest.transport.view', ['tour' => $tour,]) }}" class="color-secondary row-3">
                        <x-slot:icon>{{ Icon::list() }}</x-slot:icon>
                        View Transport Manifest
                    </x-admin.popup-button>
                </div>
            </div>
        </div>
    </div>
</div>

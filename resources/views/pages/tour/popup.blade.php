@php
    /** @var App\Models\Tour\Tour $tour */
@endphp
<div class="panel-overlay tour-options hidden">
    <a href="#" class="panel-close plain-anchor" onclick="hideOverlay('.panel-overlay')">
        X
    </a>
    <div class="panel-grid panel-grid-3-3">
        @can('update', \App\Models\Merchandise\Merchandise::class)
        <x-admin.popup-button href="{{ route('tours.fulfil', ['tour' => $tour,]) }}" class="color-danger row-1">
            <x-slot:icon>action-redo</x-slot:icon>
            Fulfil Merchandise
        </x-admin.popup-button>
        @endcan
        @if($tour->protected && $tour->has_atol_certificate)
        <x-admin.popup-button href="{{ route('tours.atol', ['tour' => $tour,]) }}" class="color-info row-1">
            <x-slot:icon>folder-alt</x-slot:icon>
            Export ATOL Certificates
        </x-admin.popup-button>
        @endif
        @can('costing', \App\Models\Tour\Tour::class)
        <x-admin.popup-button href="{{ route('tours.costing', ['tour' => $tour,]) }}" class="color-warning row-2">
            <x-slot:icon>chart</x-slot:icon>
            View Costing
        </x-admin.popup-button>
        @endcan
        <x-admin.popup-button href="{{ route('tours.rooming', ['tour' => $tour,]) }}" class="color-secondary row-3">
            <x-slot:icon>list</x-slot:icon>
            View Rooming
        </x-admin.popup-button>
        <x-admin.popup-button href="{{ route('tours.rooming', ['tour' => $tour, 'notes' => false,]) }}" class="color-secondary row-3">
            <x-slot:icon>list</x-slot:icon>
            View Rooming (No Notes)
        </x-admin.popup-button>
    </div>
</div>

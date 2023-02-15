@php
    /** @var App\Models\Tour\Tour $tour */
@endphp
<div class="panel-overlay tour-options hidden">
    <a href="#" class="panel-close plain-anchor" onclick="hideOverlay('.panel-overlay')">
        X
    </a>
    <div class="panel-grid panel-grid-5-7">
        @can('update', \App\Models\Tour\Tour::class)
        <x-admin.popup-button href="{{ route('tours.edit', ['tour' => $tour,]) }}" class="color-success row-1">
            <x-slot:icon>note</x-slot:icon>
            Edit Tour
        </x-admin.popup-button>
        @endcan
        @can('update', \App\Models\Merchandise\Merchandise::class)
        <x-admin.popup-button href="{{ route('tours.fulfil', ['tour' => $tour,]) }}" class="color-danger row-2">
            <x-slot:icon>action-redo</x-slot:icon>
            Fulfil Merchandise
        </x-admin.popup-button>
        @endcan
        @if($tour->protected && $tour->has_atol_certificate)
        <x-admin.popup-button href="{{ route('tours.atol', ['tour' => $tour,]) }}" class="color-info row-2">
            <x-slot:icon>folder-alt</x-slot:icon>
            Export ATOL Certificates
        </x-admin.popup-button>
        @endif
        @can('create', \App\Models\Quote\Quote::class)
        <x-admin.popup-button href="{{ route('quotes.create', ['tour' => $tour,]) }}" class="color-blue row-3">
            <x-slot:icon>wallet</x-slot:icon>
            Create Quote
        </x-admin.popup-button>
        @endcan
        @can('costing', \App\Models\Tour\Tour::class)
        <x-admin.popup-button href="{{ route('tours.costing', ['tour' => $tour,]) }}" class="color-warning row-4">
            <x-slot:icon>chart</x-slot:icon>
            View Costing
        </x-admin.popup-button>
        @endcan
        <x-admin.popup-button href="{{ route('tours.rooming', ['tour' => $tour,]) }}" class="color-secondary row-5">
            <x-slot:icon>list</x-slot:icon>
            View Rooming
        </x-admin.popup-button>
        <x-admin.popup-button href="{{ route('tours.rooming', ['tour' => $tour, 'notes' => false,]) }}" class="color-secondary row-5">
            <x-slot:icon>list</x-slot:icon>
            View Rooming (No Notes)
        </x-admin.popup-button>
        <x-admin.popup-button href="{{ route('tours.manifest.activity.view', ['tour' => $tour,]) }}" class="color-secondary row-5">
            <x-slot:icon>list</x-slot:icon>
            View Activity Manifest
        </x-admin.popup-button>
    </div>
</div>

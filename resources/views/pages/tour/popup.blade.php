@php
    /** @var App\Models\Tour\Tour $tour */
@endphp
<div class="panel-overlay">
    <div class="panel-grid panel-grid-2-3">
        <x-admin.popup-button href="{{ route('tours.edit', ['tour' => $tour,]) }}" class="color-success">
            <x-slot:icon>note</x-slot:icon>
            Edit Tour
        </x-admin.popup-button>
        <x-admin.popup-button href="{{ route('tours.fulfil', ['tour' => $tour,]) }}" class="color-danger">
            <x-slot:icon>action-redo</x-slot:icon>
            Fulfil Merchandise
        </x-admin.popup-button>
        <x-admin.popup-button href="{{ route('quotes.create', ['tour' => $tour,]) }}" class="color-blue">
            <x-slot:icon>wallet</x-slot:icon>
            Create Quote
        </x-admin.popup-button>
        <x-admin.popup-button href="{{ route('tours.atol', ['tour' => $tour,]) }}" class="color-info">
            <x-slot:icon>folder-alt</x-slot:icon>
            Export ATOL Certificates
        </x-admin.popup-button>
        <x-admin.popup-button href="{{ route('tours.rooming', ['tour' => $tour,]) }}" class="color-secondary">
            <x-slot:icon>list</x-slot:icon>
            View Rooming
        </x-admin.popup-button>
        <x-admin.popup-button href="{{ route('tours.rooming', ['tour' => $tour, 'notes' => false,]) }}" class="color-secondary">
            <x-slot:icon>list</x-slot:icon>
            View Rooming (No Notes)
        </x-admin.popup-button>
    </div>
</div>

@php
    /** @var App\Models\Order\Order $order */
@endphp
<div class="modal fade common-modal-custom" id="optionOrder" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-auto-width modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
            <div class="panel-grid panel-grid-3-3 custom-panel p-0 relative bg-transparent">
                <button type="button" class="absolute right-0 top-0" data-bs-dismiss="modal" aria-label="Close">
                    {{ Icon::xmark() }}
                    </button>
                <x-admin.popup-button href="{{ route('tours.view', ['tour' => $order->tour,]) }}" class="color-info row-1">
                    <x-slot:icon>{{ Icon::tour() }}</x-slot:icon>
                    View Tour
                </x-admin.popup-button>
                <x-admin.popup-button href="{{ route('orders.migrate', ['order' => $order,]) }}" class="color-warning row-1">
                    <x-slot:icon>{{ Icon::edit() }}</x-slot:icon>
                    Change Tour
                </x-admin.popup-button>
                <x-admin.popup-button href="{{ route('orders.occupancy', ['order' => $order,]) }}" class="color-primary row-2">
                    <x-slot:icon>{{ Icon::edit() }}</x-slot:icon>
                    Edit Occupancy
                </x-admin.popup-button>
                @if($order->has_atol && !$order->cancelled)
                <x-admin.popup-button href="{{ route('orders.atol', ['order' => $order,]) }}" class="color-secondary row-2">
                    <x-slot:icon>{{ Icon::atol() }}</x-slot:icon>
                    View ATOL
                </x-admin.popup-button>
                @endif
                <x-admin.popup-button href="#" onclick="event.preventDefault();resend()" class="color-mint row-2">
                    <x-slot:icon>{{ Icon::email() }}</x-slot:icon>
                    Resend Booking Confirmation
                </x-admin.popup-button>
                @can('delete', \App\Models\Order\Order::class)
                    @if($order->cancelled)
                        <x-admin.popup-button href="#" onclick="$('#order-restore').submit()" class="color-warning row-3">
                            <x-slot:icon>{{ Icon::delete() }}</x-slot:icon>
                            Restore Order
                        </x-admin.popup-button>
                        <form class="d-none" action="{{ route('orders.restore', ['order' => $order,]) }}" method="post" id="order-restore">
                            @csrf
                        </form>
                    @else
                        <x-admin.popup-button href="#" onclick="$('#order-delete').submit()" class="color-danger row-3">
                            <x-slot:icon>{{ Icon::delete() }}</x-slot:icon>
                            Cancel Order
                        </x-admin.popup-button>
                        <form class="d-none" action="{{ route('orders.delete', ['order' => $order,]) }}" method="post" id="order-delete">
                            @csrf
                        </form>
                    @endif
                @endcan
                @if(is_otm())
                    <x-admin.popup-button href="#" onclick="$('#order-force-delete').submit()" class="color-danger row-3">
                        <x-slot:icon>{{ Icon::forceDelete() }}</x-slot:icon>
                        Force Delete Order
                    </x-admin.popup-button>
                    <form class="d-none" onsubmit="return confirm('Are you sure you wish to PERMANENTLY delete this order?')" action="{{ route('orders.delete.force', ['order' => $order,]) }}" method="post" id="order-force-delete">
                        @csrf
                    </form>
                @endif
            </div>
            </div>
        </div>
    </div>
</div>
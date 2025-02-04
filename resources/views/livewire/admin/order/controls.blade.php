<div class="flex justify-center align-middle">
    <div style="width: max-content; height: max-content;">
        <x-admin.section.card>
            <div class="panel-grid panel-grid-5-5 custom-panel p-0 relative bg-transparent" style="border-radius: 0">
                <x-admin.popup-button href="{{ route('tours.view', ['tour' => $order->tour_id]) }}" class="color-info row-1">
                    <x-slot:icon>{{ Icon::tour() }}</x-slot:icon>
                    View Tour
                </x-admin.popup-button>
                @if(config('app.features.kpt') || config('app.features.bleeding-edge'))
                    <x-admin.popup-button href="{{ route('orders.reservation', ['order' => $order,]) }}" target="_blank" class="color-info row-1">
                        <x-slot:icon>{{ Icon::view() }}</x-slot:icon>
                        View Reservation Document
                    </x-admin.popup-button>
                @endif
                <x-admin.popup-button href="{{ route('orders.itinerary', ['order' => $order,]) }}" target="_blank" class="color-info row-1">
                    <x-slot:icon>{{ Icon::view() }}</x-slot:icon>
                    View Itinerary Document
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
                {{-- Email Sending --}}
                @if(flag('reservation.invoice.mail.enabled', false))
                    <x-admin.popup-button href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to send the Reservation document to email?')) { Livewire.emit('sendReservationToEmail'); }" class="color-mint row-4">
                        <x-slot:icon>{{ Icon::email() }}</x-slot:icon>
                        Send Reservation Document
                    </x-admin.popup-button>
                @endif

                <x-admin.popup-button href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to send the booking confirmation email?')) { Livewire.emit('sendBookingConfirmation'); }" class="color-mint row-4">
                    <x-slot:icon>{{ Icon::email() }}</x-slot:icon>
                    Send Booking Confirmation
                </x-admin.popup-button>

                @if($this->getInstallmentTitle() !== null)
                    <x-admin.popup-button href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to send the {{ $this->getInstallmentTitle() }} email?')) { Livewire.emit('sendPaymentDueMail'); }" class="color-mint row-4">
                        <x-slot:icon>{{ Icon::email() }}</x-slot:icon>
                        Send {{ $this->getInstallmentTitle() }} Mail
                    </x-admin.popup-button>
                @endif

                {{-- Dangerous Controls --}}
                <x-admin.popup-button href="{{ route('orders.migrate', ['order' => $order,]) }}" class="color-warning row-5">
                    <x-slot:icon>{{ Icon::edit() }}</x-slot:icon>
                    Change Tour
                </x-admin.popup-button>
                @can('delete', \App\Models\Order\Order::class)
                    @if($order->cancelled)
                        <x-admin.popup-button href="#" onclick="$('#order-restore').submit()" class="color-warning row-5">
                            <x-slot:icon>{{ Icon::delete() }}</x-slot:icon>
                            Restore Order
                        </x-admin.popup-button>
                        <form class="d-none" action="{{ route('orders.restore', ['order' => $order,]) }}" method="post" id="order-restore">
                            @csrf
                        </form>
                    @else
                        <x-admin.popup-button href="#" onclick="$('#order-delete').submit()" class="color-danger row-5">
                            <x-slot:icon>{{ Icon::delete() }}</x-slot:icon>
                            Cancel Order
                        </x-admin.popup-button>
                        <form class="d-none" onsubmit="return confirm('Are you sure you wish to Cancel this order?')" action="{{ route('orders.delete', ['order' => $order,]) }}" method="post" id="order-delete">
                            @csrf
                        </form>
                    @endif
                @endcan
                @if(is_otm())
                    <x-admin.popup-button href="#" onclick="$('#order-force-delete').submit()" class="color-danger row-5">
                        <x-slot:icon>{{ Icon::forceDelete() }}</x-slot:icon>
                        Force Delete Order
                    </x-admin.popup-button>
                    <form class="d-none" onsubmit="return confirm('Are you sure you wish to PERMANENTLY delete this order?')" action="{{ route('orders.delete.force', ['order' => $order,]) }}" method="post" id="order-force-delete">
                        @csrf
                    </form>
                @endif
            </div>
        </x-admin.section.card>
    </div>
</div>

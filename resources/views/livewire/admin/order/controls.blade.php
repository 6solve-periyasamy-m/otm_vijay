<div class="flex justify-center align-middle">
    <div style="width: max-content; height: max-content;">
        <x-admin.section.card>
            <div class="panel-grid panel-grid-5-5 custom-panel p-0 relative bg-transparent" style="border-radius: 0">
                <x-admin.popup-button href="{{ route('tours.view', ['tour' => $order->tour_id]) }}" class="color-info row-1">
                    <x-slot:icon>{{ Icon::tour() }}</x-slot:icon>
                    View Tour
                </x-admin.popup-button>
                @if(kpt())
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
                @if((kpt()) && flag('reservation.invoice.mail.enabled', false))
                        <x-admin.popup-button href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to send the Reservation document to email?')) { Livewire.emit('sendReservationToEmail'); }" class="color-mint row-4">
                        {{-- <x-admin.popup-button href="#" wire:click.prevent="openPopupEmailForm('reservation')" class="color-mint row-4">--}}
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

                @if((kpt()) && flag('itinerary.document.mail.enabled', false))
                    <x-admin.popup-button href="#" wire:click.prevent="openPopupEmailForm('itinerary')" class="color-mint row-4">
                        <x-slot:icon>{{ Icon::email() }}</x-slot:icon>
                        Send Itinerary Document
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

    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" aria-modal="true" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg" role="document" style="margin-top:100px;">
                <div class="modal-content">
                    <!-- Header Section -->
                    <div class="modal-header">
                        <h5 class="modal-title">
                            @php
                                $headingTitle = match ($this->sendType) {
                                    'itinerary' => 'Send Itinerary Document',
                                    'reservation' => 'Send Reservation Document',
                                    'booiing' => 'Send Booking Confirmation',
                                    default => '',
                                };
                            @endphp
                            {{ $headingTitle }}
                        </h5>
                        <button type="button" class="close ms-auto" wire:click="closeModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @if($successMessage)
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ $successMessage }}
                        </div>
                    @endif

                    @if($errorMessage)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ $errorMessage }}
                        </div>
                    @endif
                    <!-- Body Section -->
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <form wire:submit.prevent="sendItineraryForm"  enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>From Email *</label>
                                        <input type="email" class="form-control" wire:model="fromEmail" required>
                                        @error('fromEmail') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>From Name *</label>
                                        <input type="text" class="form-control" wire:model="fromName" required>
                                        @error('fromName') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>To *</label>
                                <input type="email" class="form-control" wire:model="to" required>
                                @error('to') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>CC (Separate with semicolon ;)</label>
                                <input type="text" class="form-control" wire:model="ccInput" placeholder="email1@example.com; email2@example.com">
                                @error('ccInput') <span class="text-danger">{{ $message }}</span> @enderror
                                <small class="form-text text-muted">Separate multiple emails with semicolons (;)</small>
                            </div>

                            <div class="form-group">
                                <label>BCC (Separate with semicolon ;)</label>
                                <input type="text" class="form-control" wire:model="bccInput" placeholder="email1@example.com; email2@example.com">
                                @error('bccInput') <span class="text-danger">{{ $message }}</span> @enderror
                                <small class="form-text text-muted">Separate multiple emails with semicolons (;)</small>
                            </div>

                            <div class="form-group">
                                <label>Subject *</label>
                                <input type="text" class="form-control" wire:model="subject" required>
                                @error('subject') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <x-livewire.ckeditor name="emailBody" value="{{ $emailBody }}" label="Email Body" />
                                @error('emailBody') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>Additional Attachment (PDF or DOC only)</label>
                                <input type="file" class="form-control" wire:model="additionalAttachments" multiple accept=".pdf,.doc,.docx">
                                <small class="form-text text-muted">Maximum file size: 2MB. Allowed types: PDF, DOC, DOCX, Maximum 5 Attachment allowed. 
                                    @if($additionalAttachments)
                                    - <b>Total size:</b> {{ number_format($this->getTotalAttachmentsSize() / 1024, 2) }} KB / 2048 KB
                                    @endif
                                </small>
                                @error('additionalAttachments') <span class="text-danger d-block">{{ $message }}</span> @enderror
                                @error('additionalAttachments.*') <span class="text-danger d-block">{{ $message }}</span> @enderror
                                @if($additionalAttachments)
                                    <div class="mt-2">
                                        <h6>Selected file:</h6>
                                        @foreach ($additionalAttachments as $index => $file)
                                            <div class="alert alert-info d-flex justify-content-between align-items-center mb-2">
                                                <span>{{ $file->getClientOriginalName() }}</span>
                                                <div>
                                                    <span class="badge badge-light mr-2">{{ round($file->getSize() / 1024, 2) }} KB</span>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                            wire:click="removeAttachment({{ $index }})"
                                                            wire:loading.attr="disabled">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                    <!-- Footer Section -->
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal"
                                wire:loading.attr="disabled">
                            @if($successMessage) Close @else Cancel @endif
                        </button>

                        @if(!$successMessage && !$errorMessage)
                            <button type="button" class="btn btn-primary" wire:click="sendEmail"
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove>Send Email</span>
                                <span wire:loading>
                                    <i class="fas fa-spinner fa-spin"></i> Sending...
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

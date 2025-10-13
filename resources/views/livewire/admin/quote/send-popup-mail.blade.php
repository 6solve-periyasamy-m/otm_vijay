<div>
    <!-- Modal -->
    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" aria-modal="true" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Send Mail</h5>
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

                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <form wire:submit.prevent="sendEmail"  enctype="multipart/form-data">
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

                            {{-- <div class="form-group">
                                <label>CC (Separate with semicolon ;)</label>
                                <input type="text" class="form-control" wire:model="ccInput" placeholder="email1@example.com; email2@example.com">
                                @error('ccInput') <span class="text-danger">{{ $message }}</span> @enderror
                                <small class="form-text text-muted">Separate multiple emails with semicolons (;)</small>
                            </div> --}}

                            <div class="form-group">
                                <label>BCC (Separate with semicolon ;)</label>
                                <input type="text" class="form-control" wire:model="bccInput" placeholder="email1@example.com;email2@example.com">
                                @error('bccInput') <span class="text-danger">{{ $message }}</span> @enderror
                                <!-- <small class="form-text text-muted">Separate multiple emails with semicolons (;)</small> -->
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
                                <input type="file" class="form-control" wire:model="additionalAttachment" accept=".pdf,.doc,.docx">
                                <small class="form-text text-muted">Maximum file size: <b>2MB</b>. Allowed types: PDF, DOC, DOCX</small>
                                @error('additionalAttachment') <span class="text-danger">{{ $message }}</span> @enderror                                
                                @if($additionalAttachment)
                                    <div class="mt-2">
                                        <h6>Selected file:</h6>
                                        <div class="alert alert-info d-flex justify-content-between align-items-center">
                                            <span>{{ $additionalAttachment->getClientOriginalName() }}</span>
                                            <span class="badge badge-light">{{ round($additionalAttachment->getSize() / 1024, 2) }} KB</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>

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


                    <!-- <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                        <button type="button" class="btn btn-primary" wire:click="sendEmail" wire:loading.attr="disabled">
                            <span wire:loading.remove>Send Email</span>
                            <span wire:loading>Sending...</span>
                        </button>
                    </div> -->
                </div>
            </div>
        </div>
    @endif
</div>
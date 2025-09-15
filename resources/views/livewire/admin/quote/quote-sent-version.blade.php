<div x-data="{ open: @entangle('showModal') }" 
     x-show="open"
     x-on:keydown.escape.window="open = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen p-4">

        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-lg max-w-4xl w-full-70 mx-auto shadow-xl max-h-[85vh] overflow-hidden flex flex-col" style="1px solid #cbc7c7 !important;">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-2 border-b bg-gray-50">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Quote Sent Version - Reference {{ $sentQuote->built->ref ?? 'N/A' }}</h2>
                    <p class="text-sm text-gray-500"></p>
                </div>
                <button x-on:click="$wire.closeModal()" type="button" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <!-- Header -->
            <div class="flex-1 overflow-y-auto p-6">
                @if($sentQuote)
                <div class="space-y-5">
                    <div class="space-y-3" style="padding-left:20px; margin-top:10px;">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center text-blue-700">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <b>Communication</b>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-gray-600 font-medium">From:</span>
                                <p class="text-gray-900 truncate">{{ $sentQuote->from_name }} &lt;{{ $sentQuote->from_email }}&gt;</p>
                            </div>
                            <div>
                                <span class="text-gray-600 font-medium">To:</span>
                                <p class="text-gray-900 truncate">{{ $sentQuote->recipient }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600 font-medium">BCC:</span>
                                <p class="text-gray-900 truncate">{{ $sentQuote->bcc ?: '—' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600 font-medium">Subject:</span>
                                <p class="text-gray-900 truncate">{{ $sentQuote->subject }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Email Preview -->
                    <div class="space-y-2" style="padding-left:20px; width:97.5%">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center text-blue-700">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <b>Message Preview</b>
                            </h3>
                            <span class="text-xs text-gray-500">{{ str_word_count($sentQuote->email_body ?? '') }} words</span>
                        </div>
                       <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden">
                            <div class="email-content-container" style="max-height: 300px; overflow-y: auto; padding: 1rem;">
                                <div class="text-sm text-gray-800 prose prose-sm max-w-none">
                                    {!! $sentQuote->email_body ?? '<p class="text-gray-500 italic">No email content available</p>' !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attachments -->
                    @if($sentQuote->additional_attachments)
                    <div class="space-y-2" style="padding-left:20px; width:97.5%; margin-top:15px;">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center text-blue-700">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            <b>Additional Attachment</b>
                        </h3>
                        <div class="space-y-1">
                            @php
                                $attachment = $sentQuote->additional_attachments;
                                $filename = basename($attachment);
                                $path = 'livewire-tmp/' . $filename;
                            @endphp
                            <div class="flex items-center text-sm text-gray-700 bg-gray-50 rounded px-3 py-2">
                                <svg class="w-4 h-4 mr-2 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm text-gray-700 truncate" title="{{ $filename }}">
                                    {{ $filename }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- System Info -->
                    <div class="space-y-2" style="padding-left:20px; margin-top:15px;">
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide flex items-center text-blue-700">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                            <b>System Info</b>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-gray-600 font-medium">Created:</span>
                                <p class="text-gray-900 truncate">{{ f_datetime($sentQuote->created_at) }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600 font-medium">Updated:</span>
                                <p class="text-gray-900 truncate">{{ f_datetime($sentQuote->updated_at) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-3"></div>
                    <p class="text-sm text-gray-500">Loading details...</p>
                </div>
                @endif
            </div>
            <!-- Footer Actions -->
            <div class="px-6 py-4 border-t bg-gray-50">
                <div class="flex items-center justify-between space-x-3">
                    <div class="text-xs text-gray-500"></div>
                    <div class="flex space-x-2">
                        <button x-on:click="$wire.closeModal()" type="button"class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Close
                        </button>
                    </div>
                </div>
            </div>
            <!-- Footer Actions -->
        </div>

    </div>
</div>     


<style>
    .email-content-container {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 #f1f5f9;
    }
    
    .email-content-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .email-content-container::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }
    
    .email-content-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    
    .email-content-container::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    .prose {
        max-width: none;
        line-height: 1.6;
    }
    
    .prose p {
        margin-bottom: 0.75rem;
    }
    
    .prose:last-child {
        margin-bottom: 0;
    }
    
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Ensure email content is properly formatted */
    .email-content-container img {
        max-width: 100%;
        height: auto;
    }
    
    .email-content-container table {
        width: 100% !important;
        border-collapse: collapse;
    }
    
    .email-content-container table, 
    .email-content-container th, 
    .email-content-container td {
        border: 1px solid #e5e7eb;
        padding: 0.5rem;
    }
</style>

<style>
    .w-full-70 {width: 70%;}
    
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .bg-blue-50 { background-color: #eff6ff; }
    .bg-green-50 { background-color: #f0fdf4; }
    
    .border-blue-200 { border-color: #bfdbfe; }
    .border-green-200 { border-color: #bbf7d0; }
    
    .text-blue-700 { color: #1d4ed8; }
    .text-green-700 { color: #15803d; }
    
    .text-blue-900 { color: #1e3a8a; }
    .text-green-900 { color: #14532d; }
    </style>
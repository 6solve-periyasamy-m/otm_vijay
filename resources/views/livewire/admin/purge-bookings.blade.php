<div>
     <button type="button" class="btn btn-danger rounded" id="start-purge-btn" @if($isProcessing) disabled @endif>
        <i class="fas fa-trash-alt"></i>&nbsp;Start Purge
    </button>

    {{-- Force Stop Button --}}
    @if($isProcessing)
        <button type="button"
                wire:click="stopPurge"
                class="btn btn-warning rounded ms-2">
            <i class="fas fa-ban"></i>&nbsp;Force Stop
        </button>
    @endif

    {{-- Loader shown before progress bar
    @if($isProcessing)
        <div class="mt-3 text-center" id="purge-loader">
            <img src="/images/spinner-kpt.gif" alt="Loading..." width="50">
            <p class="text-muted mt-2">Preparing purge process...</p>
        </div>
    @endif
     --}}
    
    @if($isProcessing)
        <div class="mt-2">
            <div class="progress">
                <div class="progress-bar bg-success" 
                    role="progressbar" 
                    style="width: {{ ($progress / $totalCount) * 100 }}%;" 
                    aria-valuenow="{{ $progress }}" 
                    aria-valuemin="0" 
                    aria-valuemax="{{ $totalCount }}">
                    {{ $progress }} / {{ $totalCount }}
                </div>
            </div>
            <div class="text-muted mt-1">
                Progress: {{ $progress }} of {{ $totalCount }}
            </div>
        </div>
    @endif

    @if(session()->has('message'))
        <div style="margin-top:10px; color:green;">
            {{ session('message') }}
        </div>
    @endif
</div>

<script>
    document.getElementById('start-purge-btn').addEventListener('click', function() {
        if (confirm("Are you sure you want to permanently delete these bookings? This action cannot be undone.")) {
            Livewire.emit('startPurge');
        }
    });
    document.addEventListener('purge-next-batch', function () {
        setTimeout(function () {
            Livewire.emit('purgeStep');
        }, 100);
    });
</script>
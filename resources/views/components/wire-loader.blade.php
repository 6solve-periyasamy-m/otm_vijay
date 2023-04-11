<div class="waiter" @if($attributes->has('longest')) wire:loading.delay.longest @else wire:loading.delay.longer @endif>
    <x-loading-spinner center></x-loading-spinner>
</div>

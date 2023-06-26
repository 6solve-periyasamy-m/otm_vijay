<div class="col-6 col-lg-2 col-xl-2 row mx-auto">
    <div class="col-12 border-bottom text-center buy-header">
        Buy for
    </div>
    <div class="col-6 border-right">
        @if($disabled ?? false)
            <span class="btn btn-success text-dark buy-button">
                One
            </span>
        @else
            <button class="btn btn-success text-dark buy-button" wire:click="buyOne">
                One
            </button>
        @endif
    </div>
    <div class="col-6">
        <button class="btn btn-success text-dark buy-button" wire:click="buyAll">
            All
        </button>
    </div>
</div>

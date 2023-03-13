<div class="row">
    <div class="col-xl-4">
        @include('partials.admin.system.brand.card', ['brand' => $system,])
    </div>
    @foreach($this->brands as $brand)
        <div class="col-xl-4">
            @include('partials.admin.system.brand.card', ['brand' => $brand,])
        </div>
    @endforeach
</div>

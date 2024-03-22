<div class="row">
    <div class="col-md-4 col-6">
        <x-admin.system.tax.tile :bracket="$this->nullBracket" />
    </div>
    @foreach(\App\Models\System\TaxBracket::all() as $bracket)
        <div class="col-md-4 col-6">
            <x-admin.system.tax.tile :bracket="$bracket" />
        </div>
    @endforeach
</div>

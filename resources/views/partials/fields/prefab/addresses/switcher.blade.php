@php 
/**
 * @var \App\Models\Location\Address|null $address 
 * @var string|null $prefix
 */ 
$prefix = $prefix ?? "";
@endphp
@push('footer-stack')
    <script type="text/javascript">
        function {{ $prefix }}switchView() {
            let createNew = $('#{{ $prefix }}use_existing-input').is(':checked');
            if (createNew) {
                $('.{{ $prefix }}switcher-new').hide();
                $('.{{ $prefix }}switcher-existing').show();
            } else {
                $('.{{ $prefix }}switcher-new').show();
                $('.{{ $prefix }}switcher-existing').hide();
            }
        }
        $(document).ready(function () { {{ $prefix }}switchView(); });
    </script>
@endpush
<hr class="splitter"/>
<div class="form-group col-xl-6">
    <input type="checkbox" name="{{ $prefix }}use_existing" class="form-check-input"
           @if(isset($address_id)) checked @endif
    id="{{ $prefix }}use_existing-input" onchange="{{ $prefix }}switchView();">
    <label for="{{ $prefix }}use_existing-input" class="form-check-label">Use Pre-Existing Address</label>
</div>
<hr class="splitter"/>
<div class="{{ $prefix }}switcher-existing">
    @include('partials.fields.prefab.addresses.selector', ['value' => $address?->id, ])
</div>
<div class="switcher-new row">
    @include('partials.models.addresses.form', ['address' => $address, 'prefix' => $prefix, 'submit' => false,])
</div>
<hr class="splitter"/>

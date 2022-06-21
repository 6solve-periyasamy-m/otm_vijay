@push('header-ready')
    let {{ $field }}Select = $('.{{ $field }}-input');
@endpush
<div class="form-group col-12 {{ isset($width) ? 'col-xl-' . $width : '' }} {{ $divClasses ?? "" }}">
    @isset($name)
        <label for="{{ $field }}-input">{{ $name }}</label>
    @endisset
    <div class="d-flex flex-wrap justify-content-center flex-column upgrade-wrapper" style="gap: 5px">
        <select class="form-control {{ $field }}-input" id="{{ $field }}-input" name="{{ $field }}">
            @foreach($options as $key => $value)
                <option value="{{ $key }}" @if($selected == $key) selected @endif>{{ $value }}</option>
            @endforeach
        </select>
        <span class="hide-on-mobile">
            @if(!(flag('payment.required', true)))
            <a href="{{ $createRoute }}" target="{{ $target ?? '_blank' }}" class="btn btn-success"
               onclick="{{$onclick ?? ''}}">+</a>
            @endif
            <a href="{{ $purchaseRoute }}" target="{{ $targetPurchase ?? '_blank' }}" class="btn btn-primary"
           onclick="{{$onclickPurchase ?? ''}}">$</a>
        </span>
        <span class="show-on-mobile-block">
            @if(!(flag('payment.required', true)))
            <a href="{{ $createRoute }}" target="{{ $target ?? '_blank' }}" class="btn btn-success" onclick="{{$onclick ?? ''}}">Add To Cart</a>
            @endif
            <a href="{{ $purchaseRoute }}" target="{{ $targetPurchase ?? '_blank' }}" class="btn btn-primary" onclick="{{$onclickPurchase ?? ''}}">Buy Now</a>
        </span>
    </div>
</div>

@push('header-ready')
    let {{ $field }}Select = $('.{{ $field }}-input');
@endpush
<div class="form-group col-12 {{ isset($width) ? 'col-xl-' . $width : '' }} {{ $divClasses ?? "" }}">
    @isset($name)<label for="{{ $field }}-input">{{ $name }}</label>@endisset
    <div class="d-flex flex-wrap justify-content-between">
        <select class="form-control {{ $field }}-input" id="{{ $field }}-input" name="{{ $field }}">
            @foreach($options as $key => $value)
                <option value="{{ $key }}" @if($selected == $key) selected @endif>{{ $value }}</option>
            @endforeach
        </select>
        <span class="p-2">
        @if(!(\App\Repository\SettingsRepository::getBoolean('payment.required', true)))
        <a href="{{ $createRoute }}" target="{{ $target ?? '_blank' }}" class="btn btn-success d-inline ms-1" onclick="{{$onclick ?? ''}}">+</a>
        @endif
        <a href="{{ $purchaseRoute }}" target="{{ $targetPurchase ?? '_blank' }}" class="btn btn-primary d-inline ms-1" onclick="{{$onclickPurchase ?? ''}}">$</a>
        </span>
    </div>
</div>

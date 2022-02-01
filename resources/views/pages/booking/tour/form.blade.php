@extends ('layout.booking')
@section('content')
<div class="booking-form container-fluid" id="app">
    <booking-form :event="{{$event}}" :tour="{{$tour}}"></booking-form>
</div>
@endsection

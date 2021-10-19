@extends ('layout.booking')
@section('content')
<div class="container-fluid" id="app">
    <booking-form :event="{{$event}}" :tour="{{$tour}}"></booking-form>
</div>
@endsection

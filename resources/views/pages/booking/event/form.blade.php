@extends ('layout.main')
@section('content')
<div class="container-fluid" id="app">
    <booking-form :event="{{$event}}"></booking-form>
</div>
@endsection

@extends ('layout.booking')
@section('head-script')
<script>
    window.auth_user = {!! json_encode($auth_user); !!};
</script>
@endsection
@section('content')
<div class="booking-form container-fluid" id="app">
    <booking-form :event="{{$event}}" :tour="{{$tour}}" :auth_user='@json($auth_user)'></booking-form>
</div>
@endsection

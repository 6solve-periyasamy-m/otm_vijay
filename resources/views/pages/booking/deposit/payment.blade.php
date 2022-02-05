@extends ('layout.booking')
@section('content')
<div class="container-fluid">
    <form action="/booking/deposit/payment" method="post">
        <div>
            Customer name: {{$customer->full_name}}
        </div>
        <div>
            Tour: {{$booking->tour->name}}
        </div>
        <div>
            <input type="number" name="deposit" value="{{$deposit}}"/>
        </div>
        <div>
            <input type="submit" value="Pay Deposit and book tour"/>
        </div>
    </form>
</div>
@endsection

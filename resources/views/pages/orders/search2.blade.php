@extends('layout.master')

@section('title', 'All Orders')

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#orders').DataTable({fixedHeader: true,columnDefs:[{targets:0,searchable:true,visible:false}],scrollX:false});
        });
    </script>
@endsection

@section('content')
    <div class="row row justify-content-center">
        <div class="col-6 col-md-5 col-xl-3">
            <img src="{{ asset('images/octlogo.png') }}" class="maxwidth"/>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <a class="btn btn-primary float-end" href="{{ route('orders.create') }}">
                <i class="icon-plus"></i>
                <span>Create New</span>
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="orders">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Customers</th>
                    <th scope="col">Order Date</th>
                    <th scope="col">Lead Booker</th>
                    <th scope="col">Booking Reference</th>
                    <th scope="col">Tour</th>
                    <th scope="col">Order Status</th>
                </tr>
                </thead>
                @foreach($orders as $order)
                    <tr>
                        <td>
                            @foreach($order->orderCustomers as $oCustomer)
                                {{ $oCustomer->customer->first_name . ' ' . $oCustomer->customer->last_name . ', '}}
                            @endforeach
                        </td>
                        <td>{{ $order->ordered_on }}</td>
                        <td>{{ $order->leadBooker->customer->first_name . ' ' . $order->leadBooker->customer->last_name }}</td>
                        <td><a href="{{ route('orders.view', ['order' => $order->id]) }}" class="link-info"><u>{{ $order->booking_reference }}</u></a></td>
                        <td>{{ $order->tour->name }}</td>
                        <td><h6 class="badge badge-{{ $order->getStatus()['color'] }} fw-bold">{{ $order->getStatus()['status'] }}</h6></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection

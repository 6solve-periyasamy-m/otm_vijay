@extends('layout.master')

@section('title', 'All Orders')

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#orders').DataTable({
                fixedHeader: true,
                ajax: {
                    'url': "{{ route('api.orders.all') }}",
                    'type': "POST",
                    'dataSrc': "",
                    'data': {
                        '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
                        'historic': {{( $historic ?? false) ? 1 : 0 }},
                    },
                },
                columns:[
                    {
                        searchable: true,
                        visible: false,
                        data: 'travellers'
                    },
                    {
                        data: 'ordered',
                        render: {
                            '_': 'format',
                            'display': 'format',
                            'sort': 'unix',
                        },
                    },
                    {
                        data: 'lead'
                    },
                    {
                        data: 'reference',
                        render: function (data, type, row, meta) {
                            return '<a href="' + row.view + '" class="link-info"><u>' + data + '</u></a>'
                        }
                    },
                    {
                        data: 'tour',
                    },
                    {
                        data: 'passengers',
                    },
                    {
                        data: 'status',
                        render: function (data, type, row, meta) {
                            return '<h6 class="badge badge-' + data.color + ' fw-bold">' + data.status + '</h6>'
                        }
                    }
                ],
            });
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
            <a class="btn btn-success float-end" href="{{ route('orders.create') }}">
                {{ Icon::create() }}
                <span>Create New</span>
            </a>
            <a class="btn btn-primary float-end" style="margin-right: 5px;" href="{{ route('orders.all', ['historic' => !($historic ?? true),]) }}">
                <i class="icon-eye"></i>
                <span>{{ !($historic ?? true) ? "Show" : "Hide" }} Historic (Older than {{ setting('system.historic', 6) }} month(s))</span>
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped" id="orders" style="width: 100%;">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Customers</th>
                    <th scope="col">Order Date</th>
                    <th scope="col">Lead Booker</th>
                    <th scope="col">Booking Reference</th>
                    <th scope="col">Tour</th>
                    <th scope="col">Passengers</th>
                    <th scope="col">Order Status</th>
                </tr>
                </thead>
                {{--}}
                @foreach($orders as $order)
                    <tr>
                        <td>
                            @foreach($order->orderCustomers as $oCustomer)
                                {{ $oCustomer->customer->first_name . ' ' . $oCustomer->customer->last_name . ', '}}
                            @endforeach
                        </td>
                        <td>{{ f_datetime($order->ordered_on) }}</td>
                        <td>{{ $order->leadBooker->customer->first_name . ' ' . $order->leadBooker->customer->last_name }}</td>
                        <td><a href="{{ route('orders.view', ['order' => $order->id]) }}" class="link-info"><u>{{ $order->booking_reference }}</u></a></td>
                        <td>{{ $order->tour->name }}</td>
                        <td>{{ $order->customer_count }}</td>
                        <td><h6 class="badge badge-{{ $order->status->color() }} fw-bold">{{ $order->status->description() }}</h6></td>
                    </tr>
                @endforeach
                {{--}}
            </table>
        </div>
    </div>
@endsection

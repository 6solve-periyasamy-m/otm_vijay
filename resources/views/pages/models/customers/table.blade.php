@extends('layout.master')

@section('title', 'All Customers')

@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#customer').DataTable({fixedHeader: true});
        });
    </script>
@endsection

@section('content')
    <div class="card">
        <div class="card-body text-end">
            <a class="btn btn-primary text-white" href="{{ route('customers.create') }}">
                <i class="icon-plus"></i>
                Create New
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="customer" style="width: 100%;" class="table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Date of Birth</th>
                    <th scope="col">Home Address</th>
                    <th scope="col">Mobile Number</th>
                    <th scope="col">Passport Expiry Date</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                @foreach($customers as $customer)
                    @include('partials.models.customers.row', [
                    'customer' => $customer,
                    'name' => $customer->title . ' ' . $customer->first_name . ' ' . $customer->last_name,
                    'date_of_birth' => $customer->date_of_birth,
                    'mobile_number' => $customer->mobile_number,
                    'passport_expiry_date' => $customer->passport_expiry_date,
                    'home_address' => $customer->homeAddress->address_line_1 . ', ' . $customer->homeAddress->country
                    ])
                @endforeach
            </table>
        </div>
    </div>
@endsection

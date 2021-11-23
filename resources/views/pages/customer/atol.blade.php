@extends('layout.customer')

@section('title', 'ATOL Certificate Generator')

@section('content')  
<div class="container-fluid">    
    <div class="row">
        <atol-certificate
                travellers="Traveller1, Traveller2, Traveller3 and Traveller4"
                passengers="4"
                tour="The Tour Details"
                flight-outward="Flight Outward Details"
                flight-inward="Flight Inward Details"
                atol="ATOL123123123123"
                issuer-long="Octopus Travel Matrix Company"
                issuer="Octopus Travel Matrix"
                msg="Customised ATOL Certificate Generator"
            >
        </atol-certificate>
    </div>
</div>
@endsection

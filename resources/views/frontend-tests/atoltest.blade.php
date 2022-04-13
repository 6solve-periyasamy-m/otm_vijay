@extends ('layout.master')
@section('header-script')
<style>
    .red-border {
        border: 1rem double red;
        margin: 1rem 2rem 1rem;
    }
    .bordered {
        border 1px black solid;
        padding: 1rem;
        margin: 1rem;
    }
</style>
@endsection
@section('content')
<div class="container-fluid page-testing" id="app">
    <h5>Octopus Travel Matrix</h5>
    <h1>ATOL Test</h1>
    <a href="/">HOME</a>
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
    ></atol-certificate>
</div>
@endsection

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
    <h1>Frontend Tests</h1>
    <a href="/">HOME</a>
    <div>
        <font-awesome-icon icon="user-secret" /> fontawesome is working 
    </div>
    <span>
        <font-awesome-icon icon="user-secret" /> fontawesome is working 
    </span>
    <vue-test></vue-test>
    <div class="addredbordertest">
        This div will have a double red border added by jQuery
        <div class="row bordered">
            <div class="col">
                Bootstrap grid
            </div>
            <div class="col">
                is working <font-awesome-icon icon="user-secret" />
            </div>
        </div>
    </div>
</div>
@endsection

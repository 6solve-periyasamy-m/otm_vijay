@extends ('layout.main')
@section('content')
    <div class="container-fluid" id="app">
 <example-component></example-component>
 <example-dupe></example-dupe>
        <div class="row">
            <div class="col-sm-6">
                <div class="py-2">
                    <img class="img-fluid" src="{{ asset('images/octlogo.png') }}" width="472" height="472" />
                </div>
            </div>
            <div class="col-sm-6 centercol">
                <div class="text-center">
                    <h1 class="tourheader">Octopus Travel Matrix Booking Form</h1>
                </div>
            </div>
        </div>

        <div class="bgclass" id="bgclass">
            <div class="row">
                <div class="col-sm-1"></div>
                <div id="accordion" class="col-sm-10">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-1 dropdownbutt">
                                <button class="btn btn-link cardhead" data-toggle="collapse" data-target="#collapseOne"
                                    aria-expanded="true" aria-controls="collapseOne">
                                    Lead Traveller details
                                </button>
                            </h5>
                        </div>

                        <div id="collapseOne" 
                            class="collapse show"
                            aria-labelledby="headingOne"
                            data-parent="#accordion">
                            <div class="card-body">
                                <form action="/default.php" method="get">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="md-form">
                                                <label type="form-label" for="fname">First name</label>
                                                <input type="text" name="fname" id="fname" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="md-form">
                                                <label class="form-label" for="lname">Last name</label>
                                                <input type="text" name="lname" id="lname" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="md-form">
                                                <label class="form-label" for="email">E-mail</label>
                                                <input type="email" name="email" id="email" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="md-form">
                                                <label class="form-label" for="phoneno">Phone number</label>
                                                <input type="number" name="phoneno" id="phoneno" class="form-control">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-5">
                                            <select class="customdropdown">
                                                <option value="" disabled selected>Contact number type</option>
                                                <option value="1">Mobile</option>
                                                <option value="2">Home</option>
                                                <option value="3">Buisness</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-6">
                                            <div class="md-form">
                                                <label class="form-label" for="conno">Contact number</label>
                                                <input type="number" name="conno" id="conno" class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">

                                            <div class="md-form">
                                                <div class="input-group">
                                                    <label class="form-label" for="datepicker">Date of Birth</label>
                                                    <input type="text" name="datepicker" class="form-control" id="datepicker">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="customformcheck">
                                                <input class="form-check-input" type="checkbox" name="billing" value="True"
                                                    id="billing">
                                                <label class="form-check-label" for="billing">
                                                    Use same delivery and billing address
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h1>&nbsp</h1>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-sm-6">
                                            <div class="md-form">
                                                <label class="form-label" for="adl1">Address line 1</label>
                                                <input type="text" name="adl1" id="adl1" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="md-form" for="adl2">
                                                <label class="form-label">Address line 2</label>
                                                <input type="text" name="adl2" id="adl2" class="form-control">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-sm-6">
                                            <div class="md-form">
                                                <label class="form-label" for="adl3">Address line 3</label>
                                                <input type="text" name="adl3" id="adl3" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-sm-6">
                                            <div class="md-form">
                                                <label class="form-label" for="town">Town</label>
                                                <input type="text" name="town" id="town" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="md-form">
                                                <label class="form-label" for="county">County</label>
                                                <input type="text" name="county" id="county" class="form-control">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-sm-6">
                                            <div class="md-form">
                                                <label class="form-label" for="postcode">Postcode</label>
                                                <input type="text" name="postcode" id="postcode" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="md-form">
                                                <label class="form-label" for="country">Country</label>
                                                <input type="text" name="country" id="country" class="form-control">
                                            </div>
                                        </div>

                                    </div>
                            </div>
                            </form>
                        </div>

                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-1">
                                    <button class="btn btn-link collapsed cardhead" data-toggle="collapse"
                                        data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Additional Travellers
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                data-parent="#accordion">
                                <div class="card-body">
                                    <p>Please input data for any additional travellers that are accompanying you.</p>
                                    <p>Use the add button to add more travellers or remove to delete entries.</p>

                                    <div class="EPT" id="extraPersonTemplate">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="md-form">
                                                    <label class="form-label">First name</label>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="md-form">
                                                    <label class="form-label">Last name</label>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="md-form">
                                                    <label class="form-label">E-mail</label>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="md-form">
                                                    <label class="form-label">Phone number</label>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-5">
                                                <select class="customdropdown">
                                                    <option value="" disabled selected>Contact number type</option>
                                                    <option value="1">Mobile</option>
                                                    <option value="2">Home</option>
                                                    <option value="3">Buisness</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-6">
                                                <div class="md-form">
                                                    <label class="form-label">Contact number</label>
                                                    <input type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <br>




                                    </div>
                                    <br>
                                    <button type="button" class="btn btn-success" id="addrow">Add</button>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingThree">
                                <h5 class="mb-1">
                                    <button class="btn btn-link collapsed cardhead" data-toggle="collapse"
                                        data-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                        Terms and conditions
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                data-parent="#accordion">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-2 customimg">
                                            <img class="img-fluid" src="{{ asset('images/iconban1.png') }}" width="472"
                                                height="472">
                                        </div>
                                        <div class="col-sm-8">
                                            <br>
                                            <br>
                                            <br>
                                            <p class="terms">
                                                By clicking confirm, you are accepting the terms and conditions as set
                                                out
                                                by the travel provider which can be seen at the links below.
                                                Please check the box to confirm you have read the terms of service.
                                            </p>
                                            <br>
                                            <a href="https://octopus-computers.com/terms-and-conditions/">Octopus TM
                                                Terms
                                                of service</a>
                                            <br>
                                            <br>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1"
                                                    value="option1">
                                                <label class="form-check-label" for="inlineCheckbox1">I agree to the
                                                    listed
                                                    terms and conditions</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 customimg">
                                            <img class="img-fluid" src="{{ asset('images/iconban2.png') }}" width="472"
                                                height="472">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1"></div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-5">
                    <button type="button" class="btn btn-success cont" id="contbutt">Continue</button>
                </div>
                <div class="col-sm-1"></div>
            </div>
            <div class="row">
                <p></p>
            </div>
        </div>

    </div>
@endsection

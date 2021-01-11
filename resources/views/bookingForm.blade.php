@extends ('layout.main')

<body class="antialiased">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="py-2">
                    <img class="img-fluid" src="{{ asset('res/octlogo.png') }}" width="472" height="472">
                </div>
            </div>
            <div class="col-sm-6 centercol">
                <div class="text-center">
                    <h1 class="tourheader">The OTM Decemeber 2020 Demo tour</h1>
                    <p class="socials">@OctopusTravelMatrix</p>
                    <p class="socials">@OctopusComputers</p>
                    <p class="socials">@RyanCatlin</p>
                    <p class="socials">@JosephOgulskij</p>
                </div>
            </div>
        </div>

	<div class="bgclass" id="bgclass">
	<br>
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

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="md-form">
                                        <input type="text" id="fnameform" class="form-control">
                                        <label for="fnameform">First name</label>
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

                            <div class="row">
                                <div class="col-sm-6">

                                    <div class="md-form">
                                        <div class="input-group">
                                            <label class="form-label">Date of Birth</label>
                                            <input type="text" class="form-control" id="datepicker">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="customformcheck">
                                        <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                        <label class="form-check-label" for="defaultCheck1">
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
                                        <label class="form-label">Address line 1</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="md-form">
                                        <label class="form-label">Address line 2</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="md-form">
                                        <label class="form-label">Address line 3</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="md-form">
                                        <label class="form-label">Town</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="md-form">
                                        <label class="form-label">County</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="md-form">
                                        <label class="form-label">Postcode</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="md-form">
                                        <label class="form-label">Country</label>
                                        <input type="text" class="form-control">
                                    </div>
                                </div>

                            </div>
                        </div>
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
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
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
                                    data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Terms and conditions
                                </button>
                            </h5>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                            data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-2 customimg">
                                        <img class="img-fluid" src="{{ asset('res/iconban1.png') }}" width="472"
                                            height="472">
                                    </div>
                                    <div class="col-sm-8">
                                        <br>
                                        <br>
                                        <br>
                                        <p class="terms">
                                            By clicking confirm, you are accepting the terms and conditions as set out
                                            by the travel provider which can be seen at the links below.
                                            Please check the box to confirm you have read the terms of service.
                                        </p>
                                        <br>
                                        <a href="https://octopus-computers.com/terms-and-conditions/">Octopus TM Terms
                                            of service</a>
                                        <br>
                                        <br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="inlineCheckbox1"
                                                value="option1">
                                            <label class="form-check-label" for="inlineCheckbox1">I agree to the listed
                                                terms and conditions</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 customimg">
                                        <img class="img-fluid" src="{{ asset('res/iconban2.png') }}" width="472"
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
        <br><br>

    </div>
    </div>
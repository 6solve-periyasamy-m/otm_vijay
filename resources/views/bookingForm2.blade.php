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
                                    Flights/Accommodation
                                </button>
                            </h5>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                    <label class="form-label">Flying from
                                    <br>
                                    <select class="customdropdown">
                                        <option value="" disabled selected>Select</option>
                                        <option value="1">default</option>
                                        <option value="2">default2</option>
                                        <option value="3">default3</option>
                                    </select>
                                    </label>
                                    </div>
                                    
                                    <div class="col-sm-6">
                                    <label class="form-label">Flying to
                                    <br>
                                    <select class="customdropdown">
                                        <option value="" disabled selected>Select</option>
                                        <option value="1">default</option>
                                        <option value="2">default2</option>
                                        <option value="3">default3</option>
                                    </select>
                                    </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-5">
                        <button type="button" class="btn btn-success cont" id="contbutt">Continue</button>
                    </div>
                    <div class="col-sm-1">
                    </div>
                </div>
            </div>

            <br><br>

        </div>
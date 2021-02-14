<template>
<div id="accordion" class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-1 dropdownbutt">
                <button class="btn btn-link cardhead" @click="toggleFlightsCard">
                    Flights
                </button>
            </h5>
        </div>

        <div id="flights-card" v-if="showFlightsCard">
            <div class="card-body">
                <h4> Flight information </h4>
                <br>
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
    <div class="card">
        <div class="card-header" id="headingOne">
            <h5 class="mb-1 dropdownbutt">
                <button class="btn btn-link cardhead" @click="toggleAccommodationCard">
                    Accomodation
                </button>
            </h5>
        </div>

        <div id="accommodation-card" v-if="showTermsConditions">
            <div class="card-body">
                <h4> Accommodation </h4>
                <div class="accomdiv">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5> Accommodation title </h5>
                        </div>
                        <div class="col-sm-6">
                            <h5 class="formright"> Region </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <p> Description : This text has to be smaller in case there is information
                                here that the user wants to enter but I doubt it would ever be this long
                                but you never know do you </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <h5> Room type </h5>
                        </div>
                        <div class="col-sm-4">
                            <h5> Board type </h5>
                        </div>

                        <div class="col-sm-4 formright">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                            <h5 class="tickboxpad">Book me</h5>
                        </div>

                    </div>
                    <div class="row">
                        <hr class="solid">
                        </hr>
                    </div>
                </div>

                <div class="accomdiv">
                    <div class="row">
                        <div class="col-sm-6">
                            <h5> The Sticky Octopus Inn </h5>
                        </div>
                        <div class="col-sm-6">
                            <h5 class="formright"> Neverland </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <p> A beautiful family-run inn with a nautical theme. Voted the best
                                imaginary bed and breakfast 1945, this professional accommodation will
                                fit your every need. </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <h5> Double bed </h5>
                        </div>
                        <div class="col-sm-4">
                            <h5> Full Board </h5>
                        </div>

                        <div class="col-sm-4 formright">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                            <h5 class="tickboxpad">Book me</h5>
                        </div>
                    </div>
                    <div class="row">
                        <hr class="solid">
                        </hr>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" id="headingTwo">
            <h5 class="mb-1">
                <button class="btn btn-link collapsed cardhead" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    Inclusions/Add-ons
                </button>
            </h5>
        </div>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <h4> Inclusions </h4>
                        <br>
                        <p> These services are included in your package as default: </p>
                        <pre>-Octopus brewing co. craft beer tasting
-Hot springs bathing session
-Winter market trip
-Octopus tour snapback hat
                            </pre>
                    </div>
                    <div class="col-sm-6">
                        <h4> Add-ons </h4>
                        <br>
                        <p> Please tick any optional add on you would like to add to the booking: </p>
                        <div class="addonholder">

                            <div class="addonentry">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="addontag">-Boat tour</p>
                                    </div>
                                    <div class="col">
                                        <p class="pricetag">£19.99</p>
                                    </div>
                                    <div class="col-1">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                    </div>
                                </div>
                            </div>

                            <div class="addonentry">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="addontag">-Scuba Diving lesson</p>
                                    </div>
                                    <div class="col">
                                        <p class="pricetag">£49.99</p>
                                    </div>
                                    <div class="col-1">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                    </div>
                                </div>
                            </div>

                            <div class="addonentry">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="addontag">-Seafood tasting platter</p>
                                    </div>
                                    <div class="col">
                                        <p class="pricetag">£29.99</p>
                                    </div>
                                    <div class="col-1">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                    </div>
                                </div>
                            </div>

                            <div class="addonentry">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="addontag">-Skydiving competiton entry</p>
                                    </div>
                                    <div class="col">
                                        <p class="pricetag">£59.99</p>
                                    </div>
                                    <div class="col-1">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                    </div>
                                </div>
                            </div>

                            <div class="addonentry">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="addontag">-Music studio day</p>
                                    </div>
                                    <div class="col">
                                        <p class="pricetag">£16.99</p>
                                    </div>
                                    <div class="col-1">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                    </div>
                                </div>
                            </div>

                            <div class="addonentry">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="addontag">-Mountain bike trail</p>
                                    </div>
                                    <div class="col">
                                        <p class="pricetag">£9.99</p>
                                    </div>
                                    <div class="col-1">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                    </div>
                                </div>
                            </div>

                            <div class="addonentry">
                                <div class="row">
                                    <div class="col-6">
                                        <p class="addontag">-Supercar rental</p>
                                    </div>
                                    <div class="col">
                                        <p class="pricetag">£599.99</p>
                                    </div>
                                    <div class="col-1">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <br>
                <hr class="solid">
                </hr>

                <div class="row">
                    <div class="col-0 col-sm-6">
                    </div>
                    <div class="col-8 col-sm-4">
                        <h4>Tour basic cost:</h4>
                    </div>
                    <div class="col-4 col-sm-2">
                        <h4>£599</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-0 col-sm-6">
                    </div>
                    <div class="col-8 col-sm-4">
                        <h4>Add-ons</h4>
                    </div>
                    <div class="col-4 col-sm-2">
                        <h4>£180</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-0 col-sm-6">
                    </div>
                    <div class="col-8 col-sm-4">
                        <h4>Total: </h4>
                    </div>
                    <div class="col-4 col-sm-2">
                        <h4>£779</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            showFlightsCard:boolean = false,
            showAccmodationCard:boolean = false
        }
    },
    methods: {
        toggleFlightsCard() {
            this.showFlightsCard = !this.showFlightsCard
        },
        toggleAccommodationCard() {
            this.showAccmodationCard = !this.showAccommodation
        }
    }
}
</script>

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
            <h3>Outward</h3>
            <flight-information type="outward"></flight-information>
            <h3>Return</h3>
            <flight-information type="return"></flight-information>
        </div>
    </div>

    <div class="card">
        <div class="card-header" id="headingOne">
            <h5 class="mb-1 dropdownbutt">
                <button class="btn btn-link cardhead" @click="toggleAccommodationCard">
                    Accommodation
                </button>
            </h5>
        </div>

        <div id="accommodation-card" v-if="showAccommodationCard">
            <div class="card-body">
                <h4> Accommodation </h4>
                <h5>Location 1</h5>
                <accommodation-details></accommodation-details>
                <h5>Location 2</h5>
                <accommodation-details></accommodation-details>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header" id="headingTwo">
            <h5 class="mb-1 dropdownbutt">
                <button class="btn btn-link cardhead" @click="toggleInclusionsCard">
                    Inclusions/Add-ons
                </button>
            </h5>
        </div>
        <div v-if="showInclusionsCard">
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

                <hr class="solid" />

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
    <div class="card">
        <div class="card-header">
            <h5 class="mb-1 dropdownbutt">
                <button class="btn btn-link cardhead" @click="togglePaymentsCard">
                    Payment
                </button>
            </h5>
        </div>
        <div class="card-body" v-if="showPaymentsCard">
            <div class="row">
                <payment-schedule status="new" tour="1" :total_price="10000" :passengers="4"></payment-schedule>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import AccommodationDetails from './AccommodationDetails.vue'
import FlightInformation from './FlightInformation.vue'
export default {
  components: { AccommodationDetails },
    data() {
        return {
            showFlightsCard: false,
            showAccommodationCard: false,
            showInclusionsCard: false,
            showPaymentsCard: false
        }
    },
    mounted() {
    },
    methods: {
        toggleFlightsCard() {
            this.showFlightsCard = !this.showFlightsCard
        },
        toggleAccommodationCard() {
            this.showAccommodationCard = !this.showAccommodationCard
        },
        toggleInclusionsCard() {
            this.showInclusionsCard = !this.showInclusionsCard
        },
        togglePaymentsCard() {
            this.showPaymentsCard = !this.showPaymentsCard
        }
    }
}
</script>

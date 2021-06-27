<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-1 dropdown-button">
                    <button class="btn btn-link cardhead" @click="toggleTermsConditions">
                        Terms and conditions
                    </button>
                </h5>
            </div>
            <div v-if="showTermsConditions">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-2 customimg">
                            <img class="img-fluid" width="472" height="472">
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
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                <label class="form-check-label" for="inlineCheckbox1">I agree to the
                                    listed
                                    terms and conditions</label>
                            </div>
                        </div>
                        <div class="col-sm-2 customimg">
                            <img class="img-fluid"  width="472" height="472">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Booking Form Terms and Conditions active.')
        },
        data() {
            return {
                showTermsConditions: false
            }
        },
        methods: {
            toggleTermsConditions() {
                this.showTermsConditions = !this.showTermsConditions
            }
        }
    }

</script>

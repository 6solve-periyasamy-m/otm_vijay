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
                <TermsAndConditions :tourdata="tourdata"></TermsAndConditions>
                <div class="card-body">
                  <p class="terms">By clicking confirm, you are accepting the terms and conditions as set out by the travel provider which can be seen at the links below.  Please check the box to confirm you have read the terms of service.</p>
                  <p v-if="false" class="terms">Link to terms: <a href="https://octopus-computers.com/terms-and-conditions/" target="_blank">Octopus TM Terms of service</a></p>
              </div>
              <div class="card-body">
                  <div class="form-check form-check-inline">
                  <input class="form-check-input" @change="checkAccept" name="inlineCheckbox1" v-model="accepted" type="checkbox" value="accept">
                  <label class="form-check-label" for="inlineCheckbox1">I agree to the listed terms and conditions</label>
              </div>
              <div class="pull-right card-body">
                  <img class="img-fluid" src="/images/logo.png" width="120" height="120">
                  <img class="img-fluid" src="/images/sample-atol.jpg" width="120" height="120">
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</template>

<script>
  import TermsAndConditions from "./TermsAndConditions.vue"
  import { bus } from '../bus'
  export default {
    props: ['tour'],
    mounted() {
        console.log("Booking Form Terms and Conditions active.")
        this.tourdata = this.tour
    },
    data() {
        return {
            showTermsConditions: false,
            accepted: false,
            tourData: {}
        };
    },
    methods: {
        toggleTermsConditions() {
            this.showTermsConditions = !this.showTermsConditions
        },
        checkAccept() {
            console.log('checkAccept', this.accepted)
            if (this.accepted) {
              this.showTermsConditions = false
            }
            bus.$emit('TermsAgreed', this.accepted)
        }
    },
    components: { TermsAndConditions }
  }
</script>
<style>
  .pull-right {
    text-align: right;
  }
</style>

<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-1">
                    <button class="btn btn-link cardhead">
                        Transportation
                    </button>
                    <p>Form section for booking or confirming ground transport including options available to support activities</p>
                </h5>
            </div>
            <div class="card-body compress">
                <div class="row">
                    <div class="col-sm-12">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import dates from '../utilities'
import BookingFormFlightSelector from './BookingFormFlightSelector.vue'
import { bus } from '../bus'
import Vue from 'vue'
/**
Transport definition

 */
export default {
    components: { BookingFormFlightSelector },
    props: ['tour', 'order_id', 'order_token'],
    data() {
        return {
            debug: false,
            activated: false,
            token: null
        }
    }
}
</script>

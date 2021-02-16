<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">OTM Booking Form version 0.03 PRERELEASE</div>

                    <div class="card-body">
                        <bookingform-header></bookingform-header>
                            <booking-form-lead></booking-form-lead>
                            <booking-form-additional></booking-form-additional>
                            <booking-form-details></booking-form-details>
                            <bookingform-terms></bookingform-terms>
                        <bookingform-footer></bookingform-footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DonutMenu from './donut-menu.vue'
import financeMenu from './finance-menu.vue'
    export default {
        components: { financeMenu },
            mounted() {
                console.log('BookingForm mounted.')
            }
    }
</script>

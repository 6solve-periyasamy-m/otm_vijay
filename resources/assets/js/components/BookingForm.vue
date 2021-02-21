<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">OTM Booking Form version 0.1.0 PRERELEASE - display only - backend not connected </div>

                    <div class="card-body">
                        <bookingform-header></bookingform-header>
                            <booking-form-tour></booking-form-tour>
                            <booking-form-lead></booking-form-lead>
                            <booking-form-additional></booking-form-additional>
                            <booking-form-flights></booking-form-flights>
                            <booking-form-accommodation></booking-form-accommodation>
                            <booking-form-payment></booking-form-payment>
                            <booking-form-terms></booking-form-terms>
                        <bookingform-footer></bookingform-footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import BookingFormTour from './BookingFormTour.vue'
import DonutMenu from './donut-menu.vue'
import financeMenu from './finance-menu.vue'
    export default {
        components: { financeMenu, BookingFormTour },
            mounted() {
                console.log('BookingForm mounted.')
            }
    }
</script>

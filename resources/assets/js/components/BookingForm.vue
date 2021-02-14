<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">
                    <div class="card-header">Booking Form</div>

                    <div class="card-body">
                        <bookingform-header></bookingform-header>
                            <!-- donut-menu></donut-menu -->
                            <tour-menu></tour-menu>
                            <bookingform-lead></bookingform-lead>
                            <bookingform-additional></bookingform-additional>
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
    export default {
        mounted() {
            console.log('BookingForm mounted.')
        }
    }
</script>

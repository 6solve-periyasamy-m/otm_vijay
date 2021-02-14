<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">Booking Form</div>

                    <div class="card-body">
                        <bookingform-header></bookingform-header>
                            <!-- donut-menu></donut-menu -->
                            <tour-menu></tour-menu>
                            <bookingform-lead></bookingform-lead>
                            <bookingform-additional></bookingform-additional>
                            <booking-form-details></booking-form-details>
                            <!-- <tour-menu></tour-menu>
                            <finance-menu></finance-menu>
                            <extras-menu></extras-menu> -->
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

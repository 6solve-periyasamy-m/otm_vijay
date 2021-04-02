<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">OTM Booking Form version 0.1.0 PRERELEASE - display only - backend not connected </div>

                    <div class="card-body">
                        <bookingform-header></bookingform-header>
                            <h1 v-if="event != null">{{event.event_title}}</h1>
                            <h2 v-if="tour != null">{{tour.title}} From {{ startDate(event) }} To {{endDate(event) }}</h2>
                            <booking-form-tour v-if="event != null && tour == null" :event="event"></booking-form-tour>
                            <booking-form-tour v-if="event == null && tour == null"></booking-form-tour>
                            <booking-form-lead></booking-form-lead>
                            <booking-form-additional></booking-form-additional>
                            <div v-if="tour">
                                <booking-form-flights :tour="tour"></booking-form-flights>
                                <booking-form-accommodation></booking-form-accommodation>
                                
                                <booking-form-payment></booking-form-payment>
                                <booking-form-terms></booking-form-terms>
                            </div>
                        <bookingform-footer></bookingform-footer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import BookingFormTour from './BookingFormTour.vue'
import dates from '../utilities'
import { bus } from '../main'
    export default {
        props: ['tour', 'event'],
        components: { BookingFormTour },
            mounted() {
                console.log('BookingForm mounted.')
                console.log('tour', this.tour)
            },
            // created() {
            //     bus.$on('selectFlight', (data) => {
            //         this.flight = data
            //     })
            // },
            methods: {
                startDate(event) {
                    console.log(event.event_start_date)
                    return dates.makeDateFromString(event.event_start_date)
                },
                endDate(event) {
                    return dates.makeDateFromString(event.event_end_date)
                },
            }
    }
</script>

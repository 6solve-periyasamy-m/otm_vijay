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
                            <booking-form-lead :tour="tour" :order_id="order_id"></booking-form-lead>
                            <booking-form-additional :tour="tour" :order_id="order_id"></booking-form-additional>
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
import { bus } from '../bus'
    export default {
        props: ['tour', 'event'],
        components: { BookingFormTour },
            mounted() {
                console.log('BookingForm mounted.')
                console.log('tour', this.tour)
            },
            created() {
                this.getOrderId();
            },
            data() {
                return {
                    token: '',
                    travellers: [],
                    order_id: 0
                }
            },
            // created() {
            //     bus.$on('selectFlight', (data) => {
            //         this.flight = data
            //     })
            // },
            methods: {
                getOrderId() {
                    axios.post('/api/booking/create-order', {
                        tour: this.tour.id,
                        event: this.event.id
                    })
                    .then(response => {
                        this.order_id = response.data.order.order_id
                        this.token = response.data.order.token
                        console.log(response)
                        bus.$emit('setOrderToken', this.token)
                        console.log(response)
                    })
                    .catch(err => {
                        console.log('error creating an order', e)
                    })
                },
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

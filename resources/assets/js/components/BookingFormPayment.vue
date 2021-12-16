<template>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-1 dropdownbutt">
                <button class="btn btn-link cardhead" @click="togglePayments">
                    Payment
                </button>
            </h5>
        </div>
        <div class="card-body" v-if="paymentsActive">
            <div class="row">
                <div class="summary">
                    
                </div>
                <payment-schedule status="new" tour="1" :total_price="10000" :passengers="4"></payment-schedule>
                <payment-installments></payment-installments>
            </div>
            <div class="row">
    
            </div>
        </div>
    </div>
</div>
</template>

<script>
import axios from "axios"
import { bus } from '../bus'
export default {
    props: ['tour'],
    data() {
        return {
            moduleName: 'Payments',
            booking_token: null,
            debug: false,
            paymentsActive: false,
            booking: {}
        }    
    },
    created() {
        let that = this
        bus.$on('setBookingToken', (bookingData) => {
            that.booking_token = bookingData
            that.debug && console.log(`>>>> ${that.moduleName} module: tour: ${that.tour.name}, booking ${that.booking_token}`)
            that.loadBooking(that.booking_token)
        })
    },
    mounted() {

    },
    methods: {
        togglePayments() {
            this.paymentsActive = !this.paymentsActive
        },
        loadPaymentSchedule() {
            let that = this
            axios.get(`/api/bookings/payment-schedules`)
                .then(response => {
                    that.paymentSchedule = response.data.paymentSchedule
                })
                .catch(error => {
                    console.log(error)
                })
        },
        loadBooking(token) {
            let that = this
            axios.get(`/api/booking/summary/${token}/gather`)
                .then(response => {
                    that.booking = response.data.booking
                    console.log('booking data ', that.booking)
                })
                .catch(error => {
                    console.log(error)
                })
        }
    }

}
</script>
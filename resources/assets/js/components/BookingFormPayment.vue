<template>

</template>

<script>import axios from "axios"

export default {
    props: ['tour'],
    data() {
        return {
            moduleName: 'Payments',
            booking_token: null,
            debug: false,
            booking: {}
        }    
    },
    created() {
        bus.$on('setBookingToken', (bookingData) => {
            that.booking_token = bookingData
            that.debug && console.log(`>>>> ${that.moduleName} module: tour: ${that.tour.name}, booking ${that.booking_token}`)
            loadBooking()
        })
    },
    mounted() {

    },
    methods: {
        loadBooking() {
            let that = this
            axios.get(`/bookings/${token}/gather`)
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
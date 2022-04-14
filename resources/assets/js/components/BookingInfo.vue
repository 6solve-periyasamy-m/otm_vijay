
<template>
    <div class="panel">
    <h3>Booking Info</h3>
        <p><i>NB: Provisional, statistics may not be accurate</i></p>
        <div>Bookings in progress {{active}} </div>
        <div>Unique customers for accommodation {{customers}} </div>
    </div>
</template>
<script>
export default {
    mounted() {
        this.getBookingData()
    },
    data() {
        return {
            active: 0,
            customers: 0
        }
    },
    methods: {
        getBookingData() {
            axios.get('/api/booking/info')
            .then((response) => {
                let data = response.data
                console.log(data)
                this.active = data.active
                this.customers = data.customers
            })
            .catch((error) => {
                console.log(error)
            })
        }
    }

}
</script>

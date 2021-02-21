<template>
    <div class="container">
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-1">
                    <button class="btn btn-link collapsed cardhead" @click="toggleAccommodation">
                        Accommodation
                    </button>
                </h5>
            </div>
            <div class="card-body" v-if="showAccommodation">
                <h4>Accommodation options</h4>
                <div class="row">
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            showAccommodation: false,
        }
    },
    mounted() {
        console.log('Accommodation options active')
    },
    methods: {
        toggleAccommodation() {
            this.showAccommodation = !this.showAccommodation
        },
        getHotels() {
            axios.get('api/booking/hotels/{location}')
                .then(response => (this.hotels = response.data))
                .catch(error => console.log(error.message))
        },
    },
}
</script>
<template>
    <div class="container">
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-1">
                    <button class="btn btn-link collapsed cardhead" @click="toggleAccommodation">
                        Accommodation
                    </button>
                    <p>Accommodation options for your tour group including indication of single or shared rooms requirements.</p>
                </h5>
            </div>
            <div class="card-body" v-if="showAccommodation">
                <h4>Accommodation options</h4>
                <h4 v-if="bookings.length">Booked</h4>
                <section class="col a-cards" v-if="bookings.length">
                    <article class="a-card" v-for="booking in bookings" :key="booking.id">
                        <aside><img src="https://picsum.photos/300/200" :alt="booking.accommodation.details.title"/></aside>
                        <div class="a-card__content">
                            <div class="active content">
                                <!-- input type="radio" name="accommodation" :value="accommodation.id" / --> 
                                {{booking.accommodation.details.title}}
                            </div>
                            <div class="label">
                                Check-in 
                            </div>
                            <div class="content">
                                {{bookingTime(booking.accommodation.check_in_date_time)}}
                            </div>
                            <div class="label">
                                Checkout
                            </div>
                            <div class="content">
                                {{bookingTime(booking.accommodation.check_out_date_time)}} 
                            </div>
                            <div class="label">
                                Room type
                            </div>
                            <div class="content">
                                {{booking.accommodation.room_type}} 
                            </div>
                            <div class="label">
                                Max occupancy
                            </div>
                            <div class="content">
                                {{booking.accommodation.maximum_occupancy}}
                            </div>
                            <div class="label">
                                Price
                            </div>
                            <div class="content">
                                {{booking.accommodation.sales_price}}
                            </div>
                            <div class="label">
                                Travellers
                            </div>
                            <div class="content">
                                <span v-for="traveller in travellers" :key="traveller.customer_id">
                                    {{fullName(traveller)}}
                                    <input 
                                        change="handleChange($event, booking.accommodation.inventory_id)"
                                        :checked="traveller.id == booking.orders_customer_id ? 'checked' : ''" 
                                        :name="`${traveller.email_address}`" 
                                        type="radio" 
                                    />
                                </span>
                            </div>
                        </div>
                    </article>
                </section>

                <h3>Available accommodation options</h3>
                <section class="col a-cards">
                    <article class="a-card" v-for="accommodation in accommodations" :key="accommodation.id">
                        <aside><img src="https://picsum.photos/300/200" :alt="accommodation.title"/></aside>
                        <div class="a-card__content">
                            <div class="active content">
                                {{accommodation.title}}
                            </div>
                            <div class="label">
                                Check-in 
                            </div>
                            <div class="content">
                                {{bookingTime(accommodation.check_in_date_time)}}
                            </div>
                            <div class="label">
                                Checkout
                            </div>
                            <div class="content">
                                {{bookingTime(accommodation.check_out_date_time)}} 
                            </div>
                            <div class="label">
                                Room type
                            </div>
                            <div class="content">
                                {{accommodation.room_type}} 
                            </div>
                            <div class="label">
                                Max occupancy
                            </div>
                            <div class="content">
                                {{accommodation.maximum_occupancy}}
                            </div>
                            <div class="label">
                                Price
                            </div>
                            <div class="content">
                                {{accommodation.sales_price}}
                            </div>
                            <div class="label">
                                Travellers
                            </div>
                            <div class="content">
                                <span v-for="traveller in travellers" :key="traveller.customer_id">
                                    {{fullName(traveller)}}
                                    <input 
                                        @change="handleChange($event, traveller.id, accommodation.inventory_id)"
                                        xvalue="traveller.id == booking.orders_customer_id" 
                                        
                                        :value="accommodation.id"
                                        :name="`${traveller.email_address}`" 
                                        type="radio" 
                                    /><br/>
                                </span>
                            </div>
                        </div>
                    </article>
                </section>
                <button @click="submit">Submit</button>
            </div>
        </div>
    </div>
</template>
<script>
import dates from '../utilities'
import { bus } from '../bus'
/**
 * accommodation is found related to the tour
 */
export default {
    props: ['tour'],
    data() {
        return {
            debug: 5,
            token: '',
            order_id: 0,
            showAccommodation: false,
            accommodations: [],
            bookings: [],
            travellers: [],
            option: [],
            booking: {}
        }
    },
    async mounted() {
        let that = this
        console.log('Accommodation options active', this.order_token)
        await bus.$on('setOrderToken', (token, order_id) => {
            this.debug>2 && console.log('>>> ACCOMMODATION token detected ', token)
            that.token = token
            that.order_id = order_id
            that.loadBooking()
        })
    },
    async created() {
        bus.$on('additionalTravellersLoaded', (travellers) => {
            this.debug>2 && console.log('Accommodation: travellers loaded', travellers)
            this.travellers = travellers
        })
        await this.getAccommodationOptions()
    },
    methods: {
        async loadBooking() {
            console.log('loading accommodation booking data ', this.token, `${this.tour.id}/${this.order_id}/${this.token}`)
            await axios.get(`/api/booking/accommodation/customer/${this.tour.id}/${this.order_id}/${this.token}`)
                .then(response => {
                    this.bookings = response.data.bookings
                    //this.bookings.accommodations.map(a => a.value = 1)
                    console.log('accommodations booking data loaded', this.bookings)
                })
                .catch(error => console.log(error))
        },
        handleChange(e, traveller, id){
            this.accommodations.filter(o=>o.id===id).map(o=>o.value=true)
            this.accommodations.filter(o=>o.id!==id).map(o=>o.value=false)
            console.log('accommodation handle change', this.accommodations)
            this.accommodations.map(accommodation => {
                const customer = traveller 
                const accommodation_id = e.target.value
                console.log('handleChange: customer ', e.target.value, customer, accommodation)
                if (e.target.value && customer != null) {
                    axios.post(`/api/booking/accommodation/${this.tour.id}/${customer}/${this.token}/${accommodation_id}/${this.order_id}`)
                        .then(response => {
                            console.log('accommodation change response:', response)
                        })
                        .catch(error => console.log(error))
                }
            })
        },
        submit() {
            this.accommodations.map(accommodation => {
                if (accommodation.pax) {
                    console.log('submitted: ', accommodation.pax)
                    accommodation.pax.map((booking, value) => {
                        console.log('booking', booking, value)   
                    })
                }
            })
            this.travellers.map(traveller => {
                console.log(traveller.email)
            })
        },
        fullName(t) {
            return `${t.first_name} ${t.last_name}`;
        },
        toggleAccommodation() {
            this.showAccommodation = !this.showAccommodation
        },
        async getAccommodationOptions() {
            const url = `/api/booking/accommodation/${this.tour.id}`
            await axios.get(url)
                .then(response => {
                    this.accommodations = response.data.accommodations
                })
                .catch(error => console.log(error))
        },
        getLocations() {
            axios.get('api/booking/tour/locations/{tour}')
                .then(response => this.location = response.data)
                .catch(error => console.log(error))
        },
        // matching accommodation to locations seems complex
        getHotels() {
            axios.get('api/booking/hotels/{location}')
                .then(response => this.hotels = response.data)
                .catch(error => console.log(error.message))
        },
        bookingTime(s) {
            console.log('bookingTime(s)', s)
            return dates.bookingTime(s)
        }
    }
}
</script>
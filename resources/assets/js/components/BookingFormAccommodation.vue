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
                <h4 v-if="!makeBooking">Accommodation selected</h4>
                <h3 v-if="makeBooking">Available accommodation options</h3>
                <section class="col a-cards accommodations" v-if="makeBooking">
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
                                    <div class="accommodations__traveller">{{fullName(traveller)}}</div>
                                    <input 
                                        @change="handleChange($event, traveller.id, accommodation.inventory_id)"
                                        :checked="traveller.id == booking.orders_customer_id" 
                                        :value="accommodation.id"
                                        :name="`${traveller.email_address}`" 
                                        :disabled="!occupancyRemaining(accommodation)"
                                        type="radio" 
                                    /><br/>
                                </span>
                            </div>
                        </div>
                    </article>
                    <div class="accommodations__summary">
                        <div v-for="booking in bookings" :key="booking.id">
                            <div class="card">
                                <div>
                                   {{booking.customer.first_name}} {{booking.customer.last_name}}
                                </div>
                                <div>
                                    {{booking.type}}
                                </div>
                                
                                <div>
                                    {{booking.accommodation.details.title}}
                                </div>
                                <div>
                                    {{booking.accommodation.details.description}}
                                </div>
                                <div>
                                    <div>
                                        <span v-if="shared(booking) && booking.accommodation">
                                            Shared
                                        </span>
                                       {{booking.accommodation.details.currency}} {{booking.accommodation.sales_price}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </section>
                <section v-else class="col a-cards">
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
                                {{booking.accommodation.board_type_name}}
                                <br/>
                                <span v-if="shared(booking)">Shared</span> {{booking.accommodation.room_type_name}}
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
                                Booked for
                            </div>
                            <div class="content">
                                <span v-for="traveller in travellers" :key="traveller.customer_id">
                                    <div v-if="traveller.id == booking.orders_customer_id">
                                        {{fullName(traveller)}}
                                    </div>
                                </span>
                            </div>
                        </div>
                    </article>
                </section>
                <button v-if="!makeBooking" @click="makeBooking = true">Change</button>
                <button v-else @click="submit">Change booking</button>
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
            booking: {},
            makeBooking: false,
            bookings:[],
            occupancy: []
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
        occupancyRemaining(accommodation) {
            console.log('occupancy remaining: ', accommodation)
//            this.occupancy[accommodation] = 
            // console.log('filtered', ids)
            // ids = ids.map(b => b.accommodation.accommodation_id)
            // console.log('ampped', ids)
            // const occupancy = this.accommodations.map(a => {
            //     return a.maximum_occupancy
            // })
            // console.log(ids, occupancy)
            // //this.bookings.filter(b => b.accommodation.accomomodation_id != booking.accommodation.accommodation_id).map(b => b.accommodation.accommodation_id)
            // // console.log(ids)
            // return (ids.includes(booking.accommodation.accommodation_id))
            return true
        },
        shared(booking) {
            console.log('bookings', this.bookings)
            let ids = this.bookings.filter(b => {
                return b.accommodation.id != booking.accommodation.id
            })
            if (ids.length == 0) {
                return true
            }
            return false
        },
        async loadBooking() {
            console.log('loadBooking() accommodation booking data ', this.token, `${this.tour.id}/${this.order_id}/${this.token}`)
            await axios.get(`/api/booking/accommodation/customer/${this.tour.id}/${this.order_id}/${this.token}`)
                .then(response => {
                    this.bookings = response.data.bookings
                    // reduce occupancyRemaining[accommodation.id]
                    /// this.occupancyRemaining[accommodation_id] ???
                    //this.bookings.accommodations.map(a => a.value = 1)
                    console.log('accommodations booking data loaded', this.bookings)
                })
                .catch(error => console.log(error))
        },
        handleChange(e, traveller, id) {
            this.bookings = []
            this.accommodations.filter(o=>o.id===id).map(o=>o.value=true)
            this.accommodations.filter(o=>o.id!==id).map(o=>o.value=false)
            console.log('accommodation handle change', this.accommodations)
            this.accommodations.map(accommodation => {
                const customer = traveller 
                const accommodation_id = e.target.value
                console.log('handleChange: customer ', e.target.value, customer, accommodation)
                if (e.target.value == accommodation.id && customer != null) {
                    axios.post(`/api/booking/accommodation/${this.tour.id}/${customer}/${this.token}/${accommodation_id}/${this.order_id}`)
                        .then(response => {
                            console.log('accommodation change response:', response)
                            this.loadBooking()
                            
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
            this.makeBooking = false
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
            //console.log('bookingTime(s)', s)
            return dates.bookingTime(s)
        }
    }
}
</script>
<template>
    <div class="container">
        <div class="card card-options">
            <div class="card-header">
                <h5 class="mb-1">
                    <button :disabled="ready ? false : true" class="btn btn-link cardhead" @click="toggleFlights">
                        Flights
                    </button>
                </h5>
            </div>
            <div v-if="showwait">Loading...</div>
            <div class="card-body compress" v-if="showFlights">
                <div class="card-options">
                    <div class="ept-form">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4> Group Flight </h4>
                                <p>Flights for each member, unless custom selections made</p>
                                <booking-form-flight-selector 
                                    v-model="selected_outbound"
                                    :enabled="!travellerFlightOptions.includes(true)"
                                    :custom="false"
                                    :tour="tour"
                                    :token="booking_token"
                                    :traveller="lead_traveller"
                                    :airports="airports"
                                    :flights="outbound_flights"
                                    :types="outbound_type"
                                    :selected_item="selected_outbound_flight">
                                </booking-form-flight-selector>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                {{leadTraveller}}
                                <booking-form-flight-selector
                                    v-model="selected_inbound"
                                    :enabled="!travellerFlightOptions.includes(true)"
                                    :custom="false"
                                    :tour="tour"
                                    :token="booking_token"
                                    :traveller="lead_traveller"
                                    :airports="airports"
                                    :flights="inbound_flights"
                                    :types="inbound_type"
                                    :selected_item="selected_inbound_flight">
                                </booking-form-flight-selector>
                            </div>
                        </div>
                        <div class="row" v-if="showOtherFlights">
                            <div class="col-sm-9">
                                <h4> Add On Flights </h4>
                                <div v-for="flight in other_flights" :key="flight.id">
                                    <booking-form-flight-selector 
                                        v-model="other_flight_selected"
                                        :tour="tour"
                                        :token="booking_token"
                                        :airports="airports"
                                        :flights="flights"
                                        :types="tour_flight_optional_types">
                                    </booking-form-flight-selector>
                                </div>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary" @click="customFlights()">
                                                Customise
                                            </button>
                                <button class="btn btn-primary" @click="toggleFlights">
                                                Close
                                            </button>
                            </div>
                        </div>
                        <div class="flight-customise" v-if="showCustomFlights">
                            <div class="row">
                                <div class="col-sm-3">
                                    Custom Flights
                                </div>
                                <div class="col-sm-3">
                                    Traveller
                                </div>
                                <div class="col-sm-3">
                                    First name
                                </div>
                                <div class="col-sm-3">
                                    Last name
                                </div>
                            </div>
                            <hr class="light" />
                            <div v-for="traveller in travellers" v-bind:key="traveller.order_customer_id">
                                <div class="row">
                                    <div class="col-sm-3">
                                        {{ traveller.order_customer_id }} {{ traveller.is_lead_booker ? 'Lead' : 'Additional'}}
                                    </div>
                                    <div class="col-sm-3">
                                        {{ traveller.first_name }}
                                    </div>
                                    <div class="col-sm-3">
                                        {{ traveller.last_name}}
                                    </div>
                                </div>
                                <div class="flight-options" v-if="travellerFlightOptions[traveller.order_customer_id]">
                                    <h5>Flight Options for traveller</h5>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            {{debug>5 ? unselected_outbound : ''}}
                                            <booking-form-flight-selector
                                                v-model="selected_custom_outbound_flight"
                                                :enabled="true"
                                                :custom="true"
                                                :tour="tour"
                                                :token="booking_token"
                                                :traveller="traveller"
                                                :airports="airports"
                                                :flights="unselected_outbound"
                                                :types="outbound_type"
                                                :selected_item="traveller.selected_outbound_addon">
                                            </booking-form-flight-selector>
                                            {{debug>5 ? unselected_inbound : ''}}
                                            <booking-form-flight-selector
                                                v-model="selected_custom_inbound_flight"
                                                :enabled="true"
                                                :custom="true"
                                                :tour="tour"
                                                :token="booking_token"
                                                :traveller="traveller"
                                                :airports="airports"
                                                :flights="unselected_inbound"
                                                :types="inbound_type"
                                                :selected_item="traveller.selected_inbound_addon">
                                            </booking-form-flight-selector>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import dates from '../utilities'
import BookingFormFlightSelector from './BookingFormFlightSelector.vue'
import { bus } from '../bus'
import Vue from 'vue'
/**
 * Flights selection component
 * Loads in available flights for this tour
 * asks the lead booker to select from Outbound and Inbound flights
 * and to select addons for each kind that is available
 * Any selected flights can then be booked or edited.
 */
export default {
    components: { BookingFormFlightSelector },
    props: {
        lead_traveller: Object,
        tour: Object
    },
    data() {
        return {
            debug: 4,
            booking_token: null,
            moduleName: 'Flights',
            activated: false,
            showwait: false,
            airports: [],
            flights: [],
            travellers: [],
            leadTraveller: {},

            bookings: [],
            outbound_flights: [],
            inbound_flights: [],
            other_flights: [],

            // group flight selections
            outbound_flight_selected: {},
            inbound_flight_selected: {},

            // flights displays ON/OFF
            showFlights: false,
            showCustomFlights: false,

            // otherflights feature is turned OFF (probably deprecate - it was for connections)
            showOtherFlights: false,

            // labels
            outbound_type: ['Outbound'],
            inbound_type: ['Inbound'],

            // for updating backend with a flight/tour/traveller selection
            set_outbound_flight: null,
            set_inbound_flight: null,
            // flight_tour: {},
            // flight_traveller: {},

            // for control of custom flight selection
            selected_outbound: null,
            selected_inbound: null,
            custom_outbound: null,
            custom_inbound: null,
            unselected_outbound: [],
            unselected_inbound: [],
            travellerFlightOptions: [],
            
            selected_outbound_flight: null,
            selected_inbound_flight: null,
            selected_custom_outbound_flight: null,
            selected_custom_inbound_flight: null,
            outbound_group_order: {},
            inbound_group_order: {},
            ready: false,
            orderset: []
        }
    },
    mounted() {
        let that = this
        bus.$on('debugOverride', (debug) => that.debug = debug)

        this.outbound_flights = this.flights.filter((flight) => flight.flight_type == 'Outbound')
        this.inbound_flights = this.flights.filter((flight) => flight.flight_type == 'Inbound')
        // this.other_flights = this.flights.filter((flight) => flight.flight_type != 'Outbound' && flight.flight_type != 'Inbound')
        this.debug && console.log('BookingFormFlights component mounted with flights: ', this.flights, ' for tour ', this.tour, ' check_out ', this.booking_id)
        this.debug>1 && console.log('BookingFormFlights MOUNTED: outbound flights', this.outbound_flights)
        this.debug>1 && console.log('BookingFormFlights MOUNTED: inbound flights', this.inbound_flights)
        that.ready = true
        that.activated = true
    },
    created() {
        let that = this
        this.leadTraveller = this.lead_traveller

        this.debug>1 && console.log('BookingFormFlights created', this.booking_id, that.booking_token, that.leadTraveller);

        
        bus.$on('setBookingToken', (bookingData) => {
            that.booking_token = bookingData
            that.debug && console.log(`>>>> ${that.moduleName} module: tour: ${that.tour.name}, booking ${that.booking_token}`)
            that.getFlights(that.tour)
            that.loadFlightsForBooking(that.booking_token)
        })

        bus.$on('additionalTravellersLoaded', travellers => {
            this.debug>1 && console.log(`${that.moduleName}: additionalTravellersLoaded`, travellers)
            that.travellers = travellers
        })

        // bus.$on('setBookingToken', token => {
        //     that.debug && console.log(`>>>> ${that.moduleName} : setBooking ${token} for tour ${that.tour.name}`)
        //     that.booking_token = token
        //    // console.log('BFF created setBooking handler', this.booking.token, this.booking.tour, this.booking.tour.id, this.booking.id)
        //     that.getFlights(that.tour)
        //     that.loadFlightsForBooking(that.booking_token)
        // })

        bus.$on('set_outbound', (flight_inventory_tour_id, flight_tour, traveller, custom, token) => {
            if (custom && !traveller) {
                alert('can not set outbound for a custom traveller without the traveller')
            }
            if (!custom && traveller) {
                this.debug>1 && console.log('>>>>>> group booking with traveller', traveller)
                // alert('group booking with traveller set?', traveller)
            }
            that.debug>4 && console.log('BFF set_outbound event: ', flight_inventory_tour_id, flight_tour, traveller, custom)
            if (traveller == null) {
                this.debug>1 && console.log('set_outbound: no traveller is passed in')
            } else if (that.leadTraveller == null) {
                this.debug>1 && console.log('set_outbound: leadTravller NOT set')
            } else if (traveller.id == that.leadTraveller.id && traveller.is_lead_booker && !custom) {
                that.unselected_outbound = that.outbound_flights.filter(flight => {
                    that.debug > 2 && ('unselected outbound: flight: ', flight)
                    return flight.flight_inventory_tour_id != flight_inventory_tour_id
                })
            }
            this.updateFlight(flight_inventory_tour_id, 'Outbound', flight_tour, traveller, custom, token)
        })

        bus.$on('set_inbound', (flight_inventory_tour_id, flight_tour, traveller, custom, token) => {
            if (traveller == null) {
                this.debug>1 && console.log('set_inbound: no traveller is passed in')
            } else if (that.leadTraveller == null) {
                this.debug>1 && console.log('set_inbound: no leadTravller')
            } else if (traveller.id == that.leadTraveller.id && traveller.is_lead_booker && !custom) {
                that.unselected_inbound = that.inbound_flights.filter(flight => {
                    return flight.flight_inventory_tour_id != flight_inventory_tour_id
                })
            }
            that.updateFlight(flight_inventory_tour_id, 'Inbound', flight_tour, traveller, custom, token)
        })

        bus.$on('removeBooking', (booking, flight_type, traveller) => {
            this.debug>1 && console.log('event remove ', flight_type, ' Booking', booking, 'for ', traveller, 'token', that.booking_token)
            const item = this.orderset.filter(ordr => ordr.inventory_tour_id === booking &&
                ordr.order_customer_id == traveller.order_customer_id);
            if (item.length === 1) {
                this.debug>1 && console.log('deleting booking', item[0].cod_id)
            }
            // this method is only for removing custom (addon) flights (you can only change group bookings)
            const record = {
                order_customer_id: traveller.order_customer_id,
                inventory_tour_id: booking,
                flight_type: flight_type,
                custom: true,
                token: that.booking_token
            }
            this.debug>1 && console.log('remove flight booking', record)
            axios.post(`/api/booking/flights/remove/flight`, record)
                 .then(response => {
                    that.debug>3 && console.log('remove flight response', response.data.flight)
                    const deleted_flight = response.data.flight
                    bus.$emit('flightRemoved', deleted_flight.id)
                })
                .catch(error => console.log(error));
        })
    },
    computed: {
        otherairports: function() {
            const airports = this.airports
            const items = airports.filter((airport) => {
                return airport.name != this.flight_from;
            })
            return items
        },
        disableCustomButton: function() {
            const customChanges = this.travellerFlightOptions.includes(true)
            const selections = this.selected_outbound || this.selected_inbound
            return selections || customChanges
        }
    },
    methods: {
        dmy(s) {
            return dates.makeDateFromString(s)
        },
        async toggleFlights() {
            if (this.activated) {
                this.showFlights = !this.showFlights
            }
            if (this.showFlights) {
                await this.loadFlightsForBooking(this.booking_id)
                this.showwait = false
            }
        },
        flightOptionsCustomer(id) {
            if (typeof this.travellerFlightOptions[id] == 'undefined' || this.travellerFlightOptions.length == 0 || this.travellerFlightOptions[id] == null) {
                Vue.set(this.travellerFlightOptions, id, true)
            } else {
                Vue.set(this.travellerFlightOptions, id, !this.travellerFlightOptions[id])
            }
        },
        // async getTourParty(booking_id) {
        //     let that = this
        //     await axios.get('/api/booking/tourparty', {
        //             params: {
        //                 booking_id: booking_id
        //             }
        //         })
        //         .then(response => {
        //             that.debug > 1 && console.log('getTourParty, response:', response)
        //             that.travellers = response.data
        //         })
        //         .catch(err => {
        //             console.log('ERROR loading tour party', err)
        //         })
        // },
        hasCustomFlights(traveller) {
            if (traveller.order_customer_id) {
                this.flightOptionsCustomer(traveller.order_customer_id)
                return true
            }
            return false
        },
        customFlights() {
            let state = this.showCustomFlights
            const that = this
            this.travellers.map(traveller => {
                if (that.hasCustomFlights(traveller)) {
                    state = false
                }
            })

            this.showCustomFlights = !state
        },
        async updateFlight(flight_inventory_tour_id, flight_type, flight_tour, customer, custom, token) {
            this.debug > 3 && console.log('updateFlight()', flight_inventory_tour_id, flight_type, flight_tour, customer, custom, token);
            let that = this
            if (customer == undefined || customer == null) {
                console.log('WARNING: updateFlight customer data missing')
                alert('UpdateFlight does not know the customer ... ')
                alert('Form data missing, please refresh or contact support')
                return
            }

            const data = {
                customer_id: this.lead_traveller.id,
                tour_id: that.tour.id,
                booking_id: that.booking_id,
                flight_type: flight_type,
                inventory_tour_id: flight_inventory_tour_id,
                custom: custom,
                token: token
            }
            this.debug>1 && console.log('booking a flight', data);
            await axios.post('/api/booking/flight', data)
                .then(response => {
                    that.debug > 3 && console.log('flight booking response', response)
                    that.loadFlightsForBooking(that.booking_id)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        flightSelected(flight_type, tour_component_type, traveller) {
            const that = this

            if (traveller == null) {
                console.log('WARNING: flightSelected - traveller is null')
                // alert('Form data incomplete, please refresh or contact support')
                return
            }
            that.debug>5 && console.log('>>>>> FLIGHTSELECTED: traveller', traveller)

            let bookingSelection = that.bookings.filter(b => {
                that.debug>4 && console.log('evaluation of flight ',b.flight_type === flight_type &&
                       b.tour_component_type === tour_component_type &&
                       b.customer_id === traveller.id, b.flight_type, flight_type, b.tour_component_type, tour_component_type, b.customer_id, traveller.id)
                return b.flight_type === flight_type &&
                       b.tour_component_type === tour_component_type &&
                       b.customer_id === traveller.id
            })
            that.debug>4 && console.log('>>>>>>> FLIGHTSELECTED booking filter items found', bookingSelection.length)
            that.debug>5 && console.log('>>>>>>> FLIGHTSELECTED booking filter', that.bookings, bookingSelection)

            if (typeof bookingSelection == 'undefined' || bookingSelection == null || bookingSelection.length == 0) {
                that.debug && console.log('WARNING: flightSelected no ' + tour_component_type + ' booking')
                return null
            }

that.debug>3 && console.log('>>>>>>> BookingSelection: ', bookingSelection)
            return bookingSelection
        },

        // loads current flight orders 
        async loadFlightsForBooking(booking_token) {
            if (!booking_token) {
                // alert('Form data seems to have missing data, please refresh or contact support')
                console.log('WARNING: load flights for order missing booking_id?');
                return
            } else {
                this.debug>1 && console.log('INFO: loadFlightsForBooking called for order ', booking_token)
            }
            let that = this
            let outbound = {}
            let inbound = {}
            this.showwait = true
            // loads current fight bookings if there are any
            await axios.get(`/api/booking/flight/bookings/${this.booking_token}`)
                .then(response => {
                    that.bookings = response.data.flightBooking
                    that.debug>1 && console.log('loadFlightsForBooking >>>> flights in booking', that.bookings, that.travellers[0])

                    if (that.bookings === null || that.bookings.length === 0) {
                        this.debug>1 && console.log('nothing has been booked yet')
                        that.showwait = false
                        return
                    }
                    // DEBUG BELOW
                    this.debug>4 && console.log('BookingFormFlight: flight orders', response)
                    //this.flightSelected('Inbound', 1, orders, that.travellers[1])
                    outbound = that.flightSelected('Outbound', 'Included', that.lead_traveller)[0]
                    inbound = that.flightSelected('Inbound', 'Included', that.lead_traveller)[0]
                    this.debug>3 && console.log('outbound flights selected', outbound,' inbound flights selected', inbound)
                    // set the selected_outbound_flight (group selector)
                    if (typeof outbound !== 'undefined' && outbound != null && outbound.flight_inventory_tour_id) {
                        that.selected_outbound_flight = outbound.flight_inventory_tour_id
                        if (that.debug>4) console.log('loadFlightsForBooking SELECTED OUT FLIGHT', that.selected_outbound_flight)
                    } else {
                        console.log('>>>> check outbound var', outbound, typeof outbound, outbound.flight_inventory_tour_id)
                    }

                    if (typeof inbound !== 'undefined' && inbound != null && inbound.flight_inventory_tour_id) {
                        that.selected_inbound_flight = inbound.flight_inventory_tour_id
                        if (that.debug>4) console.log('loadFlightsForBooking SELECTED IN FLIGHTS', that.selected_inbound_flight)
                    }

                    that.debug>2 && console.log('loadFlightsForBooking SELECTED GROUP FLIGHTS', that.selected_outbound_flight, that.selected_inbound_flight)

                    that.unselected_outbound = that.flights.filter(flight => {
                        return flight.flight_inventory_tour_id != that.selected_outbound_flight &&
                            flight.flight_type == 'Outbound'
                    })
                    that.unselected_inbound = that.flights.filter(flight => {
                        return flight.flight_inventory_tour_id != that.selected_inbound_flight &&
                            flight.flight_type == 'Inbound'
                    })

                    // process the addons TODO: Change orders -> bookings!
                    // TODO : addon should be for a specific traveller!  It will need to be recorded in that way
                    const outbound_addons = that.flightSelected('Outbound', 'Addon', that.travellers[0])
                    const inbound_addons  = that.flightSelected('Inbound', 'Addon', that.travellers[0])
                    if (outbound_addons) {
                        that.debug>2 && console.log('loadFlightsForBooking ADDONS', outbound_addons)
                        that.travellers.map((traveller, key) => {
                            outbound = that.flightSelected('Outbound', 1, traveller)
                            //  :selected_item="traveller.selected_outbound_addon">
                            if (typeof outbound !== 'undefined' && outbound != null && outbound.flight_inventory_tour_id) {
                                traveller['selected_outbound_addon'] = outbound.flight_inventory_tour_id
                                that.$set(that.travellers, key, traveller)
                                this.debug>3 && console.log('EMITTING setCustomFlightsForTraveller outbound', traveller, outbound)
                                bus.$emit('setCustomFlightsForTraveller', traveller, outbound.flight_inventory_tour_id)
                            }
                        })
                    }
                    if (inbound_addons) {
                        that.debug>2 && console.log('ADDONS', inbound_addons)
                        that.travellers.map((traveller, key) => {
                            inbound = that.flightSelected('Inbound', 1, traveller)
                            //  :selected_item="traveller.selected_outbound_addon">
                            if (typeof inbound !== 'undefined' && inbound != null && inbound.flight_inventory_tour_id) {
                                traveller['selected_inbound_addon'] = inbound.flight_inventory_tour_id
                                // this should work, TODO: check it seting the inventory flight id into the component?
                                that.$set(that.travellers, key, traveller)
                                // TODO: may want to check if these worked, or if required
                                // that.$set(that.travellers[key], 'selected_inbound_addon', inbound.flight_inventory_tour_id)
                                // that.selected_inbound_addon = inbound.flight_inventory_tour_id
                                bus.$emit('setCustomFlightsForTraveller', traveller, inbound.flight_inventory_tour_id)
                            }
                        })
                    }
                    that.showwait = false
                })
                .catch(error => {
                    console.log('error loading flights', error)
                })
        },
        async getAirports() {
            var that = this
            await axios.get('/api/booking/airports')
                .then(response => {
                    that.airports = response.data.airports
                })
                .catch(error => console.log('Error loading airports', error.message))
        },
        // loads the selectors for the flights related to this tour
        async getFlights(tour) {
            var that = this
            this.debug>3 && console.log('BookingFormFlights: getFlights, tour ', tour)
            await axios.get(`/api/booking/flights/tour/${tour.id}`)
                .then(response => {
                    this.debug>2 && console.log('&&&& flight response', response)
                    that.flights = response.data.data
                    that.outbound_flights = that.flights.filter((flight) => flight.flight_type == 'Outbound')
                    that.inbound_flights = that.flights.filter((flight) => flight.flight_type == 'Inbound')

                    that.getAirports()
                })
                .catch(error => console.log(error.message))
        },
        async getFlightType(tour, type = '') {
            var that = this
            await axios.get(`/api/booking/flights/${tour}/${type}`)
                .then(response => {
                    that.flights = response.data.data
                    that.getAirports()
                })
                .catch(error => console.log(error.message))
        },
    }
}
</script>

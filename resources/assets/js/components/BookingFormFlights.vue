<template>
    <div class="container">
        <div class="card card-options">
            <div class="card-header">
                <h5 class="mb-1">
                    <button :disabled="ready ? false : true" class="btn btn-link cardhead" @click="toggleFlights">
                        Flights
                    </button>
                </h5>
                <p class="caption">
                    <font-awesome-icon icon="arrow-right" />
                    Select flights options
                </p>
            </div>
            <div v-if="showwait">Loading...</div>
            <div class="card-body" v-if="showFlights">
                        <div class="row">
                            <div class="col-sm-12">
                                <h4>Group Flights</h4>
                                <p>Flights for each member, unless custom selections made</p>
                                <booking-form-flight-selector 
                                    v-model="selected_outbound"
                                    :enabled="!travelerFlightOptions.includes(true)"
                                    :custom="false"
                                    :tour="tour"
                                    :token="booking_token"
                                    :traveler="lead_traveler"
                                    :airports="airports"
                                    :flights="outbound_flights"
                                    :types="outbound_type"
                                    :selected_item="selected_outbound_flight">
                                </booking-form-flight-selector>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <booking-form-flight-selector
                                    v-model="selected_inbound"
                                    :enabled="!travelerFlightOptions.includes(true)"
                                    :custom="false"
                                    :tour="tour"
                                    :token="booking_token"
                                    :traveler="lead_traveler"
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
                                <button style="display: none;" class="btn btn-primary" @click="customFlights()">
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
                            <div v-for="traveler in travelers" v-bind:key="traveler.id">
                                <div class="row">
                                    <div class="col-sm-3">
                                        {{ traveler.id }} {{ traveler.is_lead_booker ? 'Lead' : 'Additional'}}
                                    </div>
                                    <div class="col-sm-3">
                                        {{ traveler.first_name }}
                                    </div>
                                    <div class="col-sm-3">
                                        {{ traveler.last_name}}
                                    </div>
                                </div>
                                <div class="flight-options" v-if="travelerFlightOptions[traveler.order_customer_id]">
                                    <h5>Flight Options for traveler</h5>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            {{debug>1 ? unselected_outbound : ''}}
                                            <booking-form-flight-selector
                                                v-model="selected_custom_outbound_flight"
                                                :enabled="true"
                                                :custom="true"
                                                :tour="tour"
                                                :token="booking_token"
                                                :traveler="traveler"
                                                :airports="airports"
                                                :flights="unselected_outbound"
                                                :types="outbound_type"
                                                :selected_item="traveler.selected_outbound_addon">
                                            </booking-form-flight-selector>
                                            {{debug>1 ? unselected_inbound : ''}}
                                            <booking-form-flight-selector
                                                v-model="selected_custom_inbound_flight"
                                                :enabled="true"
                                                :custom="true"
                                                :tour="tour"
                                                :token="booking_token"
                                                :traveler="traveler"
                                                :airports="airports"
                                                :flights="unselected_inbound"
                                                :types="inbound_type"
                                                :selected_item="traveler.selected_inbound_addon">
                                            </booking-form-flight-selector>
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
        tour: Object
    },
    data() {
        return {
            debug: null,
            booking_token: null,
            moduleName: 'BookingFormFlights',
            activated: false,
            showwait: false,
            airports: [],
            flights: [],
            travelers: [],
            leadTraveller: {},
            lead_traveler: {},

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

            // for updating backend with a flight/tour/traveler selection
            set_outbound_flight: null,
            set_inbound_flight: null,
            // flight_tour: {},
            // flight_traveler: {},

            // for control of custom flight selection
            selected_outbound: null,
            selected_inbound: null,
            custom_outbound: null,
            custom_inbound: null,
            unselected_outbound: [],
            unselected_inbound: [],
            travelerFlightOptions: [],
            
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
        this.debug>2 && console.log('BookingFormFlights mounted');
        bus.$on('debugOverride', (debug) => that.debug = debug)
    },
    created() {
        let that = this


        bus.$on('setBookingToken', (token) => {
            that.booking_token = token
            that.debug && console.log(`${that.moduleName} module: tour: ${that.tour.name}, booking ${that.booking_token}, lead ${that.leadTraveller}`)
            that.leadTraveller = that.loadLeadTraveler(that.booking_token).then(() => {
                that.getFlights(that.tour).then(() => {
                    that.debug>5 && console.log('BookingFormFlights loaded: outbound flights', that.outbound_flights)
                    that.debug>5 && console.log('BookingFormFlights loaded: inbound flights', that.inbound_flights)
                    that.loadFlightsForBooking(that.booking_token)

                    that.ready = true
                    that.activated = true
                })
            })
        })

        // BookingFormsAdditional: additionalTravellers loaded event
        bus.$on('AdditionalTravelersLoaded', travelers => {
            that.debug>1 && console.log(`${that.moduleName}: AdditionalTravelersLoaded event`, travelers)
            that.travelers = travelers
        })

        // Event fires when the BookingFormFlightSelector selects a flight (custom = false is a group booking)
        bus.$on('set_outbound', (flight_inventory_tour_id, flight_tour, traveler, custom, token) => {
            if (custom && !traveler) {
                alert('can not set outbound for a custom traveler without the traveler')
            }
            if (!custom && traveler) {
                that.debug>2 && console.log('FLIGHTS set_outbound: GROUP booking: traveler', traveler)
            }
            that.debug>3 && console.log('FLIGHTS set_outbound event: ', flight_inventory_tour_id, flight_tour, traveler, custom)
            if (traveler == null) {
                that.debug>1 && console.log('FLIGHTS set_outbound: no traveler is passed in')
            } else if (that.leadTraveller == null) {
                that.debug>1 && console.log('FLIGHTS set_outbound: leadTravller NOT set')
            } else if (traveler.id == that.leadTraveller.id && traveler.is_lead_booker && !custom) {
                that.unselected_outbound = that.outbound_flights.filter(flight => {
                    that.debug>2 && ('unselected outbound: flight: ', flight)
                    return flight.flight_inventory_tour_id != flight_inventory_tour_id
                })
            }
            that.debug>1 && console.log('BookingFormFlights: updateFlight called for outbound', flight_tour, traveler)
            that.updateFlight(flight_inventory_tour_id, 'Outbound', flight_tour, traveler, custom, token)
        })

        bus.$on('set_inbound', (flight_inventory_tour_id, flight_tour, traveler, custom, token) => {
            if (traveler == null) {
                that.debug>6 && console.log('FLIGHTS set_inbound: no traveler is passed in')
            } else if (that.leadTraveller == null) {
                that.debug>6 && console.log('FLIGHTS set_inbound: no leadTravller')
            } else if (traveler.id == that.leadTraveller.id && traveler.is_lead_booker && !custom) {
                that.unselected_inbound = that.inbound_flights.filter(flight => {
                    return flight.flight_inventory_tour_id != flight_inventory_tour_id
                })
            }
            that.debug>1 && console.log('FLIGHTS: updateFlight called for inbound', flight_tour, traveler)
            that.updateFlight(flight_inventory_tour_id, 'Inbound', flight_tour, traveler, custom, token)
        })

        bus.$on('removeBooking', (booking, flight_type, traveler) => {
            that.debug>3 && console.log('FLIGHTS removeBooking event ', flight_type, ' Booking', booking, 'for ', traveler, 'token', that.booking_token)
            const item = that.orderset.filter(ordr => ordr.inventory_tour_id === booking &&
                ordr.order_customer_id == traveler.order_customer_id);
            if (item.length === 1) {
                that.debug>1 && console.log('FLIGHTS deleting booking', item[0].cod_id)
            }
            // this method is only for removing custom (addon) flights (you can only change group bookings)
            const record = {
                order_customer_id: traveler.order_customer_id,
                inventory_tour_id: booking,
                flight_type: flight_type,
                custom: true,
                token: that.booking_token
            }
            that.debug>1 && console.log('FLIGHTS remove flight booking', record)
            axios.post(`/api/booking/flights/remove/flight`, record)
                 .then(response => {
                    that.debug>3 && console.log('FLIGHTS remove flight response', response.data.flight)
                    const deleted_flight = response.data.flight
                    bus.$emit('flightRemoved', deleted_flight.id)
                })
                .catch(error => console.log(error));
        })
        that.debug && console.log('FLIGHTS component created')
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
            const customChanges = this.travelerFlightOptions.includes(true)
            const selections = this.selected_outbound || this.selected_inbound
            return selections || customChanges
        }
    },
    methods: {
        async loadLeadTraveler(token) {
            let that = this
            await axios.get(`/api/booking/customer/${token}`)
                .then(response => {
                    console.log('FLIGHT GET LEAD', response)
                    that.leadTraveller = response.data.customer
                })
                .catch(error => console.log(error))
        },
        dmy(s) {
            return dates.makeDateFromString(s)
        },
        toggleFlights() {
            if (this.activated) {
                this.showFlights = !this.showFlights
            }
        },
        // NOT CALLED: filter out already selected item for this traveler
        // used to determine the ADDON Flight OPTIONS
        flightOptionsCustomer(id) {
            this.debug && console.log('BookingFormFlights: flightOptionsCustomer(id)', id)
            if (typeof this.travelerFlightOptions[id] == 'undefined' || this.travelerFlightOptions.length == 0 || this.travelerFlightOptions[id] == null) {
                Vue.set(this.travelerFlightOptions, id, true)
            } else {
                Vue.set(this.travelerFlightOptions, id, !this.travelerFlightOptions[id])
            }
        },
        hasCustomFlights(traveler) {
            if (traveler.id) {
                console.log('FLIGHTS : DOES traveler have custom flights?', traveler)
                // this weirdly sometimes works??? this.flightOptionsCustomer(traveler.order_customer_id)
                this.flightOptionsCustomer(traveler.id)
                return true
            }
            return false
        },
        customFlights() {
            let state = this.showCustomFlights
            const that = this
            this.travelers.map(traveler => {
                if (that.hasCustomFlights(traveler)) {
                    state = false
                }
            })

            this.showCustomFlights = !state
        },
        updateFlight(flight_inventory_tour_id, flight_type, flight_tour, customer, custom, token) {
            this.debug > 3 && console.log('updateFlight()', flight_inventory_tour_id, flight_type, flight_tour, customer, custom, token);
            let that = this
            if (customer == undefined || customer == null) {
                console.log('WARNING: updateFlight customer data missing')
                if (that.debug) {
                    alert('UpdateFlight does not know the customer ... ')
                    alert('Form data missing, please refresh or contact support')
                }
                return
            }
            this.debug && console.log('that.lead_traveler', that.lead_traveler, that.leadTraveller, that.booking_token);
            const data = {
                customer_id: that.leadTraveller.id,
                tour_id: that.tour.id,
                flight_type: flight_type,
                flight_inventory_tour_id: flight_inventory_tour_id,
                custom: custom,
                token: token
            }
            this.debug>1 && console.log('booking a flight', data);
            axios.post('/api/booking/flight', data)
                .then(response => {
                    that.debug > 3 && console.log('flight booking update response', response)
                    that.loadFlightsForBooking(that.booking_token)
                    this.$emit('ReloadBooking', this.booking_token)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        flightSelected(flight_type, tour_component_type, traveler = null) {
            const that = this
            if (!traveler) {
                traveler = that.leadTraveller
            }
            this.debug>1 && console.log('FLIGHTS flightSelected parameters: ',flight_type, tour_component_type, traveler)
            if (traveler == null) {
                console.log('WARNING: flightSelected - traveler is null')
                return
            }
            let bookingSelection = that.bookings.filter(b => {
                return b.flight_type === flight_type &&
                       b.tour_component_type === tour_component_type &&
                       b.customer_id === traveler.id
            })
            this.debug>1 && console.log('FLIGHTS bookingSelection', bookingSelection)
            if (typeof bookingSelection == 'undefined' || bookingSelection == null || bookingSelection.length == 0) {
                that.debug && console.log('WARNING: flightSelected no ' + tour_component_type + ' booking')
                return null
            }
            return bookingSelection
        },

        // loads current flight orders 
        loadFlightsForBooking(booking_token) {
            if (!booking_token) {
                alert('Flight booking: No booking active')
                return
            }
            let that = this
            let outbound = {}
            let inbound = {}
            this.showwait = true
            // loadFlightsForBooking
            axios.get(`/api/booking/flight/bookings/${this.booking_token}`)
                .then(response => {
                    that.showwait = false
                    that.bookings = response.data.flightBookings
                    that.debug>1 && console.log('loadFlightsForBooking: data', that.bookings, that.leadTraveller)
                    if (that.bookings === null || that.bookings.length === 0) {
                        this.debug>1 && console.log('nothing has been booked yet')
                        return
                    }
                    outbound = that.flightSelected('Outbound', 'Included')[0]
                    inbound = that.flightSelected('Inbound', 'Included')[0]
                    this.debug>1 && console.log('outbound flights selected', outbound,' inbound flights selected', inbound)

                    // set the selected_outbound_flight (group selector)
                    if (typeof outbound !== 'undefined' && outbound != null && outbound.flight_inventory_tour_id) {
                        that.selected_outbound_flight = outbound.flight_inventory_tour_id;   
                        if (that.debug>2) console.log('loadFlightsForBooking SELECTED OUT FLIGHT', that.selected_outbound_flight)
                    }
                    if (typeof inbound !== 'undefined' && inbound != null && inbound.flight_inventory_tour_id) {
                        that.selected_inbound_flight = inbound.flight_inventory_tour_id;     
                        if (that.debug>2) console.log('loadFlightsForBooking SELECTED IN FLIGHTS', that.selected_inbound_flight)
                    }
                    that.debug>2 && console.log('loadFlightsForBooking SELECTED GROUP FLIGHTS', that.selected_outbound_flight, that.selected_inbound_flight)

                    that.unselected_outbound = that.flights.filter(flight => {
                        return flight.flight_inventory_tour_id != that.selected_outbound_flight && flight.flight_type == 'Outbound'
                    })
                    that.unselected_inbound = that.flights.filter(flight => {
                        return flight.flight_inventory_tour_id != that.selected_inbound_flight && flight.flight_type == 'Inbound'
                    })

                    // process the addons TODO: Change orders -> bookings!
                    // TODO : addon should be for a specific traveler!  It will need to be recorded in that way
                    this.travelers.map(traveler => {
                        const outbound_addons = that.flightSelected('Outbound', 'Add-on', traveler)
                        const inbound_addons  = that.flightSelected('Inbound', 'Add-on', traveler)
                        this.debug>3 && console.log('BookingFormFlights: Addons selected: ', outbound_addons, inbound_addons)
                        if (outbound_addons) {
                            that.debug>2 && console.log('BookingFormFlights: loadFlightsForBooking outbound addons', outbound_addons)
                            that.travelers.map((traveler, key) => {
                                outbound = that.flightSelected('Outbound', 1, traveler)
                                //  :selected_item="traveler.selected_outbound_addon">
                                if (typeof outbound !== 'undefined' && outbound != null && outbound.flight_inventory_tour_id) {
                                    traveler['selected_outbound_addon'] = outbound.flight_inventory_tour_id
                                    that.$set(that.travelers, key, traveler)
                                    this.debug>3 && console.log('BookingFormFlights: EMITTING setCustomFlightsForTraveller outbound', traveler, outbound)
                                    bus.$emit('setCustomFlightsForTraveller', traveler, outbound.flight_inventory_tour_id)
                                }
                            })
                        }
                        if (inbound_addons) {
                            that.debug>2 && console.log('BookingFormFlights: ADDONS', inbound_addons)
                            that.travelers.map((traveler, key) => {
                                inbound = that.flightSelected('Inbound', 1, traveler)
                                //  :selected_item="traveler.selected_outbound_addon">
                                if (typeof inbound !== 'undefined' && inbound != null && inbound.flight_inventory_tour_id) {
                                    traveler['selected_inbound_addon'] = inbound.flight_inventory_tour_id

                                    // this should work, TODO: check it seting the inventory flight id into the component?
                                    that.$set(that.travelers, key, traveler)

                                    bus.$emit('setCustomFlightsForTraveller', traveler, inbound.flight_inventory_tour_id)

                                    // TODO: may want to check if these worked, or if required
                                    // that.$set(that.travelers[key], 'selected_inbound_addon', inbound.flight_inventory_tour_id)
                                    // that.selected_inbound_addon = inbound.flight_inventory_tour_id
                                }
                            })
                        }
                    })
                })
                .catch(error => {
                    that.showwait = false
                    console.log('loadFlightsForBooking >>>> error loading flights', error)
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
            this.debug>3 && console.log('BookingFormFlights: getFlights for tour ', tour.name)
            await axios.get(`/api/booking/flights/tour/${tour.id}`)
                .then(response => {
                    that.flights = response.data.data
                    if (response.data.success) {
                        that.getAirports()
                        that.debug>5 && console.log('getFlights:', that.flights)
                        that.outbound_flights = that.flights.filter((flight) => flight.flight_type == 'Outbound')
                        that.inbound_flights = that.flights.filter((flight) => flight.flight_type == 'Inbound')
                        that.debug>6 && console.log('getFlights: filtered', that.outbound_flights, that.inbound_flights)
                    } else {
                        console.log('getFlights: no flight bookings to load yet')
                    }
                })
                .catch(error => console.log(error.message))
        },
        getFlightType(tour, type = '') {
            var that = this
            axios.get(`/api/booking/flights/${tour}/${type}`)
                .then(response => {
                    that.flights = response.data.data
                    that.getAirports()
                })
                .catch(error => console.log(error.message))
        },
    }
}
</script>

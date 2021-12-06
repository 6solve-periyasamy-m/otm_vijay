<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-1">
                    <button @click="toggleTransports" class="btn btn-link cardhead">
                        Transportation
                    </button>
                    <p>Form section for booking or confirming ground transport including options available to support activities</p>
                </h5>
            </div>
            <div  v-if="activated" class="card-body compress">
                <div class="transports">
                    <div class="transport">
                        <div class="listing headings">
                            <div class="heading name">
                                Name
                            </div>
                            <div class="heading description">
                                Description
                            </div>
                            <div class="heading tour_component_type">
                                Transport Type
                            </div>
                            <div class="heading is_domestic">
                                Domestic/International
                            </div>
                            <div class="heading address_from">
                                From
                            </div>
                            <div class="heading address_to">
                                To
                            </div>
                            <div class="heading sales_price">
                                Price
                            </div>
                        </div>
                        <div v-for="transport in transports">
                            <div class="listing">
                                <div class="name">
                                    {{transport.name}}
                                </div>
                                <div class="description">
                                    {{transport.description}}
                                </div>
                                <div class="transport_type">
                                    {{transport.transport_type_name}}
                                </div>
                                <div class="is_domestic">
                                    {{transport.is_domestic ? 'Domestic' : 'International'}}
                                </div>
                                <div class="address_from">
                                    {{transport.departure_address}}
                                </div>
                                <div class="address_to">
                                    {{transport.arrival_address}}
                                </div>
                                <div class="sale_price">
                                    {{transport.sales_price}}
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
import { bus } from '../bus'
import Vue from 'vue'
/**
Transport definition

 */
export default {
    props: ['tour'],
    data() {
        return {
            debug: false,
            moduleName: 'transports',
            activated: false,
            booking_token: null,
            transports: []
        }
    },
    created() {
        let that = this
        bus.$on('setBookingToken', (bookingData) => {
            that.booking_token = bookingData
            that.debug && console.log(`>>>> ${that.moduleName} module: tour: ${that.tour.name}, booking ${that.booking_token}`)
        })
    },
    mounted() {
        this.loadTransportsInventory()
    },
    methods: {
        toggleTransports() {
            this.activated = !this.activated
        },
        loadTransportsInventory() {
            let that = this
            axios.get(`/api/booking/transports/tour/${this.tour.id}`)
                .then(response => {
                    console.log('Transports Inventory: ',response.data.transports)
                    that.transports = response.data.transports
                })
                .catch(error => {
                    console.log(error);
                })
        },
        loadTransportsBooking() {
            console.log('TransportsBooking')
        }
    }
}
</script>

<style scoped lang="scss">
    .listing {
        display: flex;
        flex-direction: row;
    }
    .listing div {
        padding: 0.25rem;
        margin: 1rem;
    }
    .listing.headings div {
        text-align: center;
    } 
    .listing .name {
        width: 12rem;
    }
    .listing .description {
        width: 24rem;
    }
    .listing .tour_component_type {
        width: 8rem;
    }
    .listing .is_domestic {
        width: 8rem;
    }
    .listing .address_from, 
    .listing .address_to {
        width: 20rem;
    }
</style>

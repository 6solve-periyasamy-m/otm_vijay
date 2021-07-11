<template>
    <div class="container">
        <div class="card card-options">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-1">
                    <button class="btn btn-link collapsed cardhead" @click="toggleAccommodation">Accommodation</button>
                    <p>Accommodation options for your tour group including indication of single or shared rooms requirements.</p>
                </h5>
            </div>
            <div v-if="showAccommodation" class="card-body ept-form">
                <h2>Accommodation options</h2>
                <!-- {{travellers}} -->
                <div class="accommodation_travellers"
                    v-for="(traveller, index) in group"
                    v-bind:key="index">
                    <div v-if="traveller.shared == false" class="accommodation_traveller">
                        <div class="accommodation_traveller__name">
                            {{traveller.first_name}} {{traveller.last_name}}
                        </div>
                        <div class="accommodation_booking-policy">
                        </div>
                        <div class="accommodation_traveller__options--labels">
                            <accommodation-room-selection
                                :order_id="order_id"
                                :tour="tour"
                                :traveller="traveller"
                                :group="group">
                            </accommodation-room-selection>
                        </div>
                    </div>
                </div>
                <button class="btn btn-default" @click="reset">Reset</button>
                <button class="btn btn-primary" @click="confirmAvailability">Confirm Availability</button>
                <p>Set your preferred accommodation selections and confirm availability.  
                To restart, Reset, then close the Accommodation panel and reopen it.  Settings are only saved once confirmed.
                Confirmed accommodation is available now, but it is not reserved until the booking is completed.
                </p>

            </div>
        </div>
    </div>
</template>

<script>
import dates from "../utilities";
import { bus } from "../bus";
import Vue from "vue";
import AccommodationRoomSelection from './AccommodationRoomSelection.vue'
/**
 * accommodation is found related to the tour
 */
function initialstate() {
    return {
        debug: 3,
        showAccommodation: false,
        accommodations: [],
        occupancy: [],
        traveller: {},
        group: [],
        others: [],
        room_selection: [],
        room_share: [],
        room_single: [],
        isShare: [],
        sharer: {},
        type: {},
    }
}
export default {
    components: { AccommodationRoomSelection },
    props: ["tour", "order_id", "order_token", "travellers"],
    data() {
        return initialstate();
    },
    created() {
        this.debug>3 && console.log('Accommodation: this.tour=', this.tour, this.order_token, this.order_id, this.travellers)
        this.group = this.others = this.travellers
        this.setup()
    },
    methods: {
        setup() {
            let that = this
            this.group.map(t => t.shared = false)
            this.getAccommodationOptions()
            bus.$on('setRoomShare', function(traveller) {
                that.group.map(t => {
                    if (t.id == traveller.sharer.id)  {
                        t.shared = true
                    }
                })
                // to reduce the others, an event hanlder in ARSelector is needed
                // that.others = that.othertravellers(traveller.sharer.id)
                that.$forceUpdate()
            })
            bus.$emit('loadOthers', that.group)
        },
        init() {
            let that = this
            this.group = []
            this.$forceUpdate()
            this.group = this.others = this.travellers
            this.group.map(t => t.shared = false)
            this.others = this.travellers
            this.getAccommodationOptions()
        },
        reset() {
            initialstate();
            this.showAccommodation = false
            this.$forceUpdate()
            this.setup()
            this.init()
            bus.$emit('AccommodationRoomSelectorInit', this.group)
            this.$forceUpdate()
            this.showAccommodation = true

                // this.group.map(t => {
                //     t.shared = false
                //     t.sharer = null
                // })
                // console.log('reset', this.group)
                // this.$forceUpdate()
        },
        confirmAvailability() {
            this.travellers.map(traveller => {
                this.debug>3 && console.log('traveller:', traveller)
                axios.post('/api/booking/accommodation/reserve', {
                    customer_id: traveller.id,
                    order_id: this.order_id,
                    type: this.type,
                    sharer_id: traveller.sharer_id
                })
                .then(response => console.log(response))
                .catch(error => console.log(error))
            })
        },
        toggleAccommodation() {
            this.showAccommodation = !this.showAccommodation
            if (this.showAccommodation) {
                this.others = this.travellers
                this.getAccommodationOptions()
            }
        },
        countOthers(traveller) {
            const others = this.othertravellers(traveller)
            return others.length
        },
        othertravellers(traveller_id) {
            let group = this.others
            this.others = group.filter(t => t.id != traveller_id)
            return this.others
        },
        // getOthers(traveller_id) {
        //     let group = this.group
        //     group = group.filter(t => t.id != traveller_id)
        //     console.log('getOthers', this.group, group)
        //     this.others = group
        //     this.$forceUpdate()
        //     return group
        // },
        // roomSelection(type, traveller) {
        //     if (type == 'single') {
        //         Vue.set(this.isShare, traveller.id, false)
        //     }
        //     if (type == 'share') {
        //         Vue.set(this.isShare, traveller.id, true)
        //         console.log('roomSelection', this.isShare[traveller.id], type, traveller, this.room_selection)
        //     }
        //     const group = this.group.filter(t => t.id != traveller.id && (t.share_id != traveller.id))

        //     console.log('roomSelection: group:', group)
        //     this.others = group
        // },
        // selectSharer(traveller) {
        //     console.log('selectSharer', traveller.id, this.sharer)
            
        //     traveller.sharer_id = this.sharer[traveller.id]
            
        //     //axios.post('/api/booking/accommodation/')
        // },
        async getAccommodationOptions() {
            const that = this;
            const url = `/api/booking/accommodation/${this.tour.id}`
            await axios.get(url)
            .then((response) => {
                this.debug>3 && console.log('getAccommodationOptions', response.data)
                that.accommodations = response.data.accommodations
                that.accommodations.map(
                    (accommodation) => {
                        that.occupancy[accommodation.accommodation_id] =
                        accommodation.maximum_occupancy
                });
            })
            .catch((error) => console.log(error))
        },
    }
}
</script>
<style scoped lang="scss">
.accommodation_traveller {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    align-content: space-between;
    &__name {
        width: 18rem;
        background: var(--light-grey);
    }
    &__options {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        &--share-with {
            width: 20rem;
            margin-left: 0;
        }
        &--single,
        &--share {
            width: 15rem;
        }
        &--labels {
            display: flex;
            flex-direction: row;
            div {
                width: 10rem;
            }
        }
    }
    @media screen and (min-width: 576px) {
        margin-left: 2rem;
        &__options {
            align-items: flex-end;
            &--single,
            &--share {
                width: 12rem;
            }
            &--labels {
                display: flex;
                flex-direction: row;
                div {
                    width: 12rem;
                }
            }
        }
    }
    @media screen and (min-width: 768px) {
        margin-left: 4rem;
        &__options {
            align-items: flex-end;
            &--single,
            &--share {
                width: 15rem;
            }
            &--labels {
                display: flex;
                flex-direction: row;
                div {
                    width: 15rem;
                }
            }
        }
    }
}
</style>
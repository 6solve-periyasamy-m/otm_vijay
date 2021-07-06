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
                    v-for="(traveller, index) in travellers"
                    v-bind:key="index">
                    {{index}}
                    <div class="accommodation_traveller">
                        <div class="accommodation_traveller__name">
                            {{traveller.first_name}} {{traveller.last_name}}
                        </div>
                        <div class="accommodation_booking-policy">
                        </div>
                        <div class="accommodation_traveller__options--labels">
                            <div>Single</div>
                            <div>Shared</div>
                            <div v-if="isShare[traveller.id]">Share with</div>
                        </div>
                        <div class="accommodation_traveller__options">
                            <div class="accommodation_traveller__options--single">
                                <input type="radio"
                                    name="type"
                                    value="single"
                                    @change="roomSelection('single',traveller)"
                                    model="room_selection[traveller]">
                            </div>
                            <div class="accommodation_traveller__options--share">
                                <input type="radio" 
                                    name="type"
                                    value="share"
                                    :disabled="checkIsShare(traveller)"
                                    @change="roomSelection('share', traveller)"
                                    model="room_selection[traveller]">
                            </div>
                            <div v-if="isShare[traveller.id]" class="accommodation_traveller_options--share-with">
                                <keep-alive>
                                    <select @change="selectSharer(traveller)" v-model="sharer[traveller.id]">
                                        <option selected disabled>Open</option>
                                        <option v-for="(share, id) in others" :key="id" :value="share.id">
                                            {{share.first_name}} {{share.last_name}}
                                        </option>
                                    </select>
                                </keep-alive>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" @click="confirmAvailability">Confirm Availability</button>
            </div>
        </div>
    </div>
</template>

<script>
import dates from "../utilities";
import { bus } from "../bus";
import Vue from "vue";
/**
 * accommodation is found related to the tour
 */
export default {
    props: ["tour", "order_id", "order_token", "travellers"],
    data() {
        return {
            debug: 3,
            showAccommodation: false,
            accommodations: [],
            occupancy: [],
            traveller: {},
            group: [],
            others: [],
            sharer: [],
            room_selection: [],
            room_share: [],
            room_single: [],
            isShare: [],
            share: {},
            sharer: {},
            type: {}
        }
    },
    created() {
        console.log('Accommodation: this.tour=', this.tour, this.order_token, this.order_id, this.travellers)
        let that = this
        this.group = this.others = this.travellers
        this.travellers.map(t => this.isShare[t] = false)
        console.log(this.travellers)
       // bus.$on('addTraveller', this.addTraveller);
        this.getAccommodationOptions()
    },
    methods: {
        checkIsShare(traveller) {
            const isShare = this.travellers.filter(t => typeof t.sharer_id!='undefined' && t.sharer_id == traveller.id)
            console.log('checkIsShare:', isShare, traveller.id, traveller.sharer_id)
            return isShare.length > 0
        },
        confirmAvailability() {
            this.travellers.map(traveller => {
                console.log('traveller:', traveller)
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
        // othertravellers(traveller_id) {
        //     let group = this.others
        //     this.others = group.filter(t => t.id != traveller_id)
        //     return this.others
        // },
        // getOthers(traveller_id) {
        //     let group = this.group
        //     group = group.filter(t => t.id != traveller_id)
        //     console.log('getOthers', this.group, group)
        //     this.others = group
        //     this.$forceUpdate()
        //     return group
        // },
        roomSelection(type, traveller) {
            if (type == 'single') {
                Vue.set(this.isShare, traveller.id, false)
            }
            if (type == 'share') {
                Vue.set(this.isShare, traveller.id, true)
                console.log('roomSelection', this.isShare[traveller.id], type, traveller, this.room_selection)
            }
            const group = this.group.filter(t => t.id != traveller.id && (t.share_id != traveller.id))

            console.log('roomSelection: group:', group)
            this.others = group
        },
        selectSharer(traveller) {
            console.log('selectSharer', traveller.id, this.sharer)
            
            traveller.sharer_id = this.sharer[traveller.id]
            
            //axios.post('/api/booking/accommodation/')
        },
        async getAccommodationOptions() {
            const that = this;
            const url = `/api/booking/accommodation/${this.tour.id}`
            await axios.get(url)
            .then((response) => {
                console.log('getAccommodationOptions', response.data)
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
    flex-direction: column;
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
            width: 10rem;
            margin-left: 0;
        }
        &--single,
        &--share {
            width: 10rem;
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
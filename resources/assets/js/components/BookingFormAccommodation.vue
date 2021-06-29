<template>
    <div class="container">
        <div class="card card-options">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-1">
                    <button class="btn btn-link collapsed cardhead" @click="toggleAccommodation">
                            Accommodation
                        </button>
                    <p>
                        Accommodation options for your tour group including indication of single or shared rooms requirements.
                    </p>
                </h5>
            </div>
            <div v-if="showAccommodation" class="card-body ept-form">
                <h2>Accommodation options</h2>
                <div class="accommodation_travellers"
                    v-for="(traveller, index) in travellers"
                    v-bind:key="index">
                    {{index}}
                    <div class="accommodation_traveller">
                        <div class="accommodation_traveller__name">
                            {{traveller.first_name}} {{traveller.last_name}}
                        </div>
                        <div class="accommodation_traveller__options--labels">
                            <div>Single Room rate</div>
                            <div>Shared Room rate</div>
                            <div>Share with</div>
                        </div>
                        <div class="accommodation_traveller__options">
                            <div class="accommodation_traveller__options--single">
                                <input type="radio" value="single" v-model="room_selection[index]">
                            </div>
                            <div class="accommodation_traveller__options--share">
                                <input type="radio" value="share" v-model="room_selection[index]">
                            </div>
                            <div class="accommodation_traveller_options--share-with">
                                <keep-alive>
                                    <select @change="selectSharer" value="sharer">
                                        <option selected disabled></option>
                                        <option v-for="(share,id) in getOthers()" v-bind:key="id">
                                            {{share.first_name}} {{share.last_name}}
                                        </option>
                                    </select>
                                </keep-alive>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import dates from "../utilities";
import { bus } from "../bus";
/**
 * accommodation is found related to the tour
 */
export default {
    props: ["tour", "order_id", "order_token", "travellers"],
    data() {
        return {
            debug: 3,
            showAccommodation: false,
            traveller: {},
            others: [],
            sharer: [],
            room_selection: [],
            room_share: [],
            room_single: []
        }
    },
    created() {
        console.log('Accommodation: this.tour=', this.tour, this.order_token, this.order_id)
        let that = this
        this.others = this.travellers
        // bus.$on('addTraveller', this.addTraveller);
    },
    methods: {
        toggleAccommodation() {
            this.showAccommodation = !this.showAccommodation
        },
        countOthers(traveller) {
            const others = this.othertravellers(traveller)
            return others.length
        },
        othertravellers(traveller) {
            let group = this.others
            this.others = group.filter(t => t.id != traveller.id)
            return this.others
        },
        getOthers() {
            return this.others
        },
        selectSharer() {
            console.log(this.sharer)
            this.others = this.others.filter(t => t.id != this.sharer.id)
        }
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
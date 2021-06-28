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
            <div class="accommodation_travellers" v-for="traveller in travellers" v-bind:key="traveller.id">
                <div class="accommodation_traveller">
                    <div class="accommodation_traveller__name">
                        {{traveller.first_name}} {{traveller.last_name}}
                    </div>
                    <div class="accommodation_traveller__options--labels">
                        <div>Single Room rate</div>
                        <div>Shared Room rate</div>
                        <div>Share with (couples)</div>
                    </div>
                    <div class="accommodation_traveller__options">
                        <div class="accommodation_traveller__options--single">
                            <input type="radio" name="room_selection" value="single">
                        </div>
                        <div class="accommodation_traveller__options--share">
                            <input type="radio" name="room_selection" value="share">
                        </div>
                        <div class="accommodation_traveller_options--share-with">
                            <select v-model="share_with">
                                <option v-for="traveller in travellers" v-bind:key="traveller.id">
                                {{traveller.first_name}} {{traveller.last_name}}
                                </option>
                            </select>                                
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
            showAccommodation: false
        }
    },
    created() {
        console.log('Accommodation: this.tour=', this.tour, this.order_token, this.order_id)
        let that = this
    },
    methods: {
        toggleAccommodation() {
            this.showAccommodation = !this.showAccommodation
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
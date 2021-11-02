<template>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-1">
                    <button class="btn btn-link cardhead" @click="toggleTours">
                        Tours
                    </button>
                </h5>
            </div>
            <div class="card-body" v-if="showTours">
                <h4> Select from our currently available tour options </h4>
                <div class="row">
                    <div class="col-sm-6" v-if="!event">
                        <label class="form-label">Event
                        <select v-model="event" @change="getTourData">
                                <option default value="">Select</option>
                                <option v-for="event in events.data" :key="event.id" :value="event.id">
                                    {{ event.name }}
                                    From: {{ startDate(event) }} To: {{ endDate(event) }}
                                </option>
                            </select>
                        </label>
                    </div>
                    <div class="col-sm-6" v-if="event">
                        <label class="form-label">Tour
                        <select v-model="tour">
                                <option default value="">Select</option>
                                <option v-for="tour in tours.data" :key="tour.id">
                                    {{ tour.name }}
                                </option>
                            </select>
                        </label>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import dates from '../utilities'
import { bus } from '../bus'
export default {
    props: ['event','tour'],
    data() {
        return {
            debug: false,
            showTours: false,
            events: [],
            tours: []
        }
    },
    async mounted() {
        let that = this
        bus.$on('debugOverride', (debug) => that.debug = debug)
        this.debug && console.log('BookingFormTour component active')

        if (this.event == undefined) {
            this.getEvents()
        }
        if (this.tour == null) {
            await this.getTours()
        }
    },
    methods: {
        startDate(event) {
            console.log(event.starts_at)
            return dates.makeDateFromString(event.starts_at)
        },
        endDate(event) {
            return dates.makeDateFromString(event.ends_at)
        },
        toggleTours() {
            this.showTours = !this.showTours
        },
        getEvents() {
            console.log('getting events');
            axios.get(`/api/booking/events`)
                .then(response => {
                    this.events = response.data
                })
                .catch(error => console.log(error.message))
        },
        getTours() {
            console.log(`getting tours for event ${this.event.id}`)
            axios.get(`/api/booking/tours/${this.event.id}`)
                .then(response => (this.tours = response.data))
                .catch(error => console.log(error.message))
        },
    }
}
</script>

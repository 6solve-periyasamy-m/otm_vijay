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
                    <div class="col-sm-6">
                        <label class="form-label">Event
                        <select v-model="event" @change="getTourData">
                                <option default value="">Select</option>
                                <option v-for="event in events.data" :key="event.id" :value="event.id">
                                    {{ event.event_title }} 
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
                                    {{ tour.title }}
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
import dates from '../utilities';
export default {
    data() {
        return {
            showTours: false,
            events: [],
            event: null,
            tours: [],
            tour: {}
        }
    },
    mounted() {
        this.getEvents()
    },
    methods: {
        startDate(event) {
            console.log(event.event_start_date)
            return dates.makeDateFromString(event.event_start_date)
        },
        endDate(event) {
            return dates.makeDateFromString(event.event_end_date)
        },
        toggleTours() {
            this.showTours = !this.showTours
        },
        getEvents() {
            axios.get(`api/booking/events`)
                .then(response => {
                    this.events = response.data
                })
                .catch(error => console.log(error.message))
        },
        getTourData() {
            console.log('requesting...', this.event)
            return this.getTours(this.event)
        },
        getTours(event_id) {
            axios.get(`api/booking/tours/${event_id}`)
                .then(response => (this.tours = response.data))
                .catch(error => console.log(error.message))
        },
    }
}
</script>

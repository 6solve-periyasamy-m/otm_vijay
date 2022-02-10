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
                <div v-if="showRegistered">
                    <h4>Accommodation Registered</h4>
                        <div>traveller name</div>
                        <div>room type</div>
                        <div>share group</div>
                    <div v-for="accommodation in accommodations" class="row">
                        <div><input type="text" readonly :value="accommodations.traveller"></div>
                        <div>
                            <select v-model="room_type">
                                <option>Select a room type</option>
                                <option v-for="room_type in room_types" :value="room_type.name"></option>
                            </select>
                        </div>
                        <div>
                            <select v-model="group_id">
                                <option>Share group selection</option>
                                <option v-for="group in assignGroups"></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            debug: false,
            moduleName: 'Accommodation',
            booking_token: null,
            showAccommodation: false,
            accommodations: [],
            room_types = ['Single', 'Twin', 'Double', 'Shared'],
            groups: []
        }
    },
    methods() {
        assignGroups() {
            return [1,2,3,4,5]
        }
    }
}
</script>
<style scoped lang="scss">
</style>
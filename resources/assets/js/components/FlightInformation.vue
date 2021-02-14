<template>
    <div class="card-body">
        <h4> Flight information </h4>
        <br>
        <div class="row">
            <div class="col-sm-6">
                <label class="form-label">Flying from
                    <select v-model="flight_from">
                        <option v-for="option in flightFromOptions" v-bind:key="option.key">
                            {{ option.text }} {{ option.value }}
                        </option>
                    </select>
                    <span>Selected: {{ flight_from }}</span>
                </label>
            </div>
            <div class="col-sm-6">
                <label class="form-label">Flying to
                    <select v-model="flight_to">
                        <option v-for="option in flightToOptions" v-bind:key="option.key">
                            {{ option.text }} {{ option.value }}
                        </option>
                    </select>
                    <span>Selected: {{ flight_to }}</span>
                </label>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            flightFromOptions: [],
            flight_from: '',
            flightToOptions: [],
            flight_to: ''
        }
    },
    mounted() {
        this.flightFromOptions = [
            {
                text: 'Select Flight',
                value: ''
            },
            {
                text: 'Flight a', 
                value: 'AA1000'
            },{
                text: 'Flight b', 
                value: 'BB5000'
            }
        ],
        this.flightToOptions = [
            {
                text: 'Select Flight',
                value: ''
            },
            {
                text: 'Flight c', 
                value: 'CY500'
            },{
                text: 'Flight d', 
                value: 'BA4500'
            }
        ]
    },
}
</script>
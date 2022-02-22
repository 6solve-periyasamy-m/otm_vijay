<template>
    <span class="header">
        <a class="control" @click="showControl=!showControl"> Controls </a>
        <div v-if="showControl">
            <div class="booking-form--control">
                <button class="btn btn-small btn-primary" @click="clearForm">Clear form</button>
                <button class="btn btn-small btn-primary" @click="showForms">Show forms</button>
            </div>
        </div>
    </span>
</template>
<script>
import { bus } from '../bus'
export default {
  props: ['tour'],
  created() {
    bus.$on('setBookingToken', (token) => {
        that.booking_token = token
        that.debug && console.log(`>>>> ${that.moduleName} module: tour: ${that.tour.name}, booking ${that.booking_token}`)
    })
  },
  data() {
    return {
        showControl: false,
        booking_token: null,
        debug: false,
    }
  },
  methods: {
    // remove cookie
    clearForm() {
    },
    // links to each form to activate one
    showForms() {
    },
    // checks the user is logged in to the backend
    isLoggedIn() {
    }
  }
}
</script>
<style scoped lang="scss">
</style>


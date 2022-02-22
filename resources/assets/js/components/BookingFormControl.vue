<template>
    <button @click="clearForm">Clear form</button>
    <button @click="showForms">Show forms</button>
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
        booking_token: null,
        debug: false,
        tour: null
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

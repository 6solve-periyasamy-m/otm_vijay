<template>
    <div v-if="auth" class="controls">
        <a class="controls-activation" @click="showControl=!showControl"> Controls </a>
        <div v-if="showControl">
            {{user.name}}
            {{bookings}}
            <div class="booking-form--control">
                <button class="btn btn-sm btn-primary" @click="clearForm">Clear form</button>
                <button class="btn btn-sm btn-primary" @click="showForms">Show forms</button>
            </div>
        </div>
    </div>
</template>
<script>
import { bus } from '../bus'
import { setCookie, getCookie, deleteCookie } from '../cookies'
export default {
  props: ['tour', 'user', 'token'],
  data() {
    return {
        showControl: false,
        booking_token: null,
        debug: false,
        bookings: [],
        auth: false
    }
  },
  created() {
    const that=this
    bus.$on('setBookingToken', (token) => {
        that.booking_token = token
        that.debug && console.log(`>>>> ${that.moduleName} module: tour: ${that.tour.name}, booking ${that.booking_token}`)
    })
    if (this.user) {
      this.auth = true
    }
  },
  mounted() {
    if (this.user.id) {
      this.getBookings()
    }
  },
  methods: {
    // get bookings for this customer
    getBookings() {
      axios.get(`/api/customer/bookings/${this.user.id}`)
        .then(response => {
          console.log('BOOKINGS',response)
          this.bookings = response.data
        })
        .catch(error => console.log(error))
    },
    // remove cookie
    clearForm() {
      deleteCookie(this.token)
    },
    storeActiveToken() {
      localStorage.active_token[this.booking_token] = 'active'
    },
    // links to each form to activate one
    showForms() {
    },
  }
}
</script>
<style scoped lang="scss">
</style>


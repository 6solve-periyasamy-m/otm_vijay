<template>
    <div class="controls">{{debug ? bookings : null}}
        <a class="controls-activation" @click="showControl=!showControl"> Controls </a>
        <div v-if="showControl">
            <div class="booking-form--control">
                <button class="btn btn-sm btn-primary" @click="clearForm">Clear form</button>
                <button v-show="login && activeTokens.length" class="btn btn-sm btn-primary" @click="controlForms">Show forms</button>
            </div>
            <div v-if="showForms && activeTokens">
                <select v-model="activateBooking" @change="activate">
                  <option default value="" placeholder="Load Tour">Select a booking to load form</option>
                  <option v-for="booking in bookings" :key="booking.token" :value="booking.token">{{booking.tour_name}} {{booking.name}}</option>
                </select>
            </div>
        </div>
    </div>
</template>
<script>
import { bus } from '../bus'
import { setCookie, getCookie, deleteCookie } from '../cookies'
export default {
  props: ['tour', 'customer', 'token_label'],
  data() {
    return {
        debug: false,
        showControl: true,
        showForms: true,
        moduleName: 'BookingFormControl',
        bookingToken: null,
        activateBooking: String,
        activeTokens: [],
        bookings: [],
        login: true,
        auth: false
    }
  },
  created() {
    const that=this
    bus.$on('setBookingToken', (current_token) => {
        console.log('CONTROL', current_token)
        that.bookingToken = current_token
        setCookie(that.token_label, that.bookingToken)
        localStorage.active_token = that.bookingToken
        that.debug && console.log(`>>>> ${that.moduleName} module: tour: ${that.tour.name}, booking ${that.bookingToken}`)
    })
    bus.$on('controlLoadBookings', () => {
      //that.findBookings()
      that.controlForms(true)
    })
  },
  mounted() {
    this.findBookings()
  },
  methods: {
    findBookings(current_token = null) {
      // console.log('findBookings: current_token is set to ', current_token)
      const tokens = JSON.parse(localStorage.getItem('tokens'))
      if (current_token == null && tokens && tokens.length) {
        current_token = tokens[0];
      }
      // console.log('findBookings: current_token is set to ', current_token)      
      tokens.map((t) => {
        // console.log('locally stored token getting booking for',t)
        this.getBookings(t)
      })
      // console.log('findBookings', this.bookings)
      this.activeTokens = localStorage.tokens
    },
    restoreActive() {
        if (localStorage.active_token) {
            this.getBookings(localStorage.active_token)
            setCookie(this.token_label, localStorage.active_token)
        } 
    },
    controlForms(show = false) {
        console.log('controlForms', this.bookings)
        if (this.bookings.length === 0) {
            this.restoreActive()
        } else
        if (localStorage.active_token !== this.bookingToken) {
            // console.log('restoring cookie to active token')
            this.restoreActive()
        }
        if (show == false) {
          this.showForms = !this.showForms
        } else {
          this.showForms = true
        }
    },
    // get bookings for this customer
    getBookings(token) {
      let that=this
      // console.log('getBookings', token)
      // if (localStorage.active_token !== token) {
      //   token = localStorage.active_token
      // }
      axios.get(`/api/booking/customer/bookings/${token}`)
        .then(response => {
          console.log('getBooking:',response.data)
          if (response.data.success && response.data.bookings != null && response.data.bookings.length) {
            that.bookings = response.data.bookings
            localStorage.setItem('tokens', JSON.stringify([]))
            console.log('recreating local', that.bookings)
            localStorage.setItem('tokens', JSON.stringify(that.bookings.map(b => b.token)))
          } else {
            console.log('not setting token as no data for it', token);
          }
        })
        .catch(error => console.log(error))
    },
    resetToken() {
      localStorage.active_token = this.activateBooking
      localStorage.setItem('tokens', JSON.stringify([this.activateBooking]));
      bus.$emit('setBookingToken', this.activateBooking)
    },
    activate() {
      console.log(`activating ${this.activateBooking}`)
      if (!this.activateBooking) {
        return
      }
      setCookie(this.token_label, this.activateBooking)
      window.location.reload(true)
    },
    // initialise form
    async initForm() {
        bus.$emit('initialiseForm')
    },
    // remove cookie
    clearForm() {
      localStorage.setItem('tokens', JSON.stringify([]));
      deleteCookie(this.token_label)
      bus.$emit('resetBookingToken')
      window.location.reload(true)
    },
    storeActiveToken() {
      // localStorage.active_token[this.bookingToken] = 'active'
    }
  }
}
</script>
<style scoped lang="scss">
</style>


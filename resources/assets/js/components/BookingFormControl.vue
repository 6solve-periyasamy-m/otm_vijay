<template>
    <div class="controls">
        <a class="controls-activation" @click="showControl=!showControl"> Controls </a>
        <div v-if="showControl">
            <div class="booking-form--control">
                <button class="btn btn-sm btn-primary" @click="clearForm">Clear form</button>
                <button v-show="login && activeTokens.length" class="btn btn-sm btn-primary" @click="controlForms">Show forms</button>
            </div>
            <div v-if="showForms && activeTokens">
                <select v-model="activateBooking" @change="activate">
                  <option default value="" placeholder="Load Tour">Select a booking to load form</option>
                  <option v-for="booking in bookings" :key="booking.token" :value="booking.token">{{booking.tour_name}}</option>
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
        showControl: true,
        showForms: true,
        moduleName: 'BookingFormControl',
        booking_token: null,
        activateBooking: String,
        activeTokens: [],
        debug: false,
        bookings: [],
        login: true,
        auth: false
    }
  },
  created() {
    const that=this
    bus.$on('setBookingToken', (current_token) => {
        console.log('CONTROL', current_token)
        that.booking_token = current_token
        that.findBookings(current_token)
        setCookie(that.token_label, that.booking_token)
        localStorage.active_token = that.booking_token
        that.debug && console.log(`>>>> ${that.moduleName} module: tour: ${that.tour.name}, booking ${that.booking_token}`)
    })
    bus.$on('restoreBooking', () => {
    })
  },
  mounted() {
    this.findBookings()
  },
  methods: {
    findBookings(current_token = null) {
console.log('findBookings: current_token is set to ', current_token)
      const tokens = JSON.parse(localStorage.getItem('tokens'))
      if (current_token == null && tokens && tokens.length) {
        current_token = tokens[0];
      }
console.log('findBookings: current_token is set to ', current_token)      
      tokens.map((t) => {
        console.log('locally stored token getting booking for',t)
        this.getBookings(t)
      })
      console.log('findBookings', this.bookings)
      this.activeTokens = localStorage.tokens
    },
    restoreActive() {
        if (localStorage.active_token) {
            this.getBookings(localStorage.active_token)
            setCookie(this.token_label, localStorage.active_token)
        } 
    },
    controlForms() {
        console.log('controlForms', this.bookings)
        if (this.bookings.length === 0) {
            this.restoreActive()
        }
        if (localStorage.active_token !== this.booking_token) {
            console.log('restoring cookie to active token')
            this.restoreActive()
        }
        this.showForms = !this.showForms
    },
    // get bookings for this customer
    getBookings(token) {
      let that=this
      console.log('getBookings', token)
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
        deleteCookie(this.token_label)
        bus.$emit('initialiseForm')
    },
    // remove cookie
    clearForm() {
      this.initForm().then(() => that.findBookings())

      // init form is an event that may not yet have happend
      //this.getBookings(localStorage.active_token)
    },
    storeActiveToken() {
      // localStorage.active_token[this.booking_token] = 'active'
    }
  }
}
</script>
<style scoped lang="scss">
</style>


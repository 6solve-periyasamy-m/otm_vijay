<template>
    <div class="controls">
        <a class="controls-activation" @click="showControl=!showControl"> Controls </a>
        <div v-if="showControl">
            <div class="booking-form--control">
                <button class="btn btn-sm btn-primary" @click="clearForm">Clear form</button>
                <button v-show="login" class="btn btn-sm btn-default" @click="controlForms">Show forms</button>
                <button v-show="login" class="btn btn-sm btn-default" @click="findBookings">Find bookings</button>
            </div>
            <div v-if="showForms">
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
        showForms: false,
        booking_token: null,
        activateBooking: String,
        debug: false,
        bookings: [],
        login: false,
        auth: false
    }
  },
  created() {
    const that=this
    bus.$on('setBookingToken', (current_token) => {
      console.log('CONTROL', current_token)
        that.booking_token = current_token
        setCookie(that.token_label, that.booking_token)
        localStorage.active_token = that.booking_token
        that.debug && console.log(`>>>> ${that.moduleName} module: tour: ${that.tour.name}, booking ${that.booking_token}`)
    })
    bus.$on('restoreBooking', () => {
    })
  },
  methods: {
    findBookings() {
      const tokens = JSON.parse(localStorage.getItem('tokens'))
      console.log(tokens)
      localStorage.setItem('tokens', JSON.stringify([]));
      tokens.map((t) => {
        console.log(t)
        this.getBookings(t)
      })
      this.getCustomerBookings()
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
    getCustomerBookings() {
      console.log(this.customer)
    },
    // get bookings for this customer
    getBookings(token) {
      let that=this
      console.log('getBookings', token)
      if (localStorage.active_token !== token) {
        token = localStorage.active_token
      }
      axios.get(`/api/booking/customer/bookings/${token}`)
        .then(response => {
          console.log('getBooking:',response)
          if (response.data.length) {
            that.bookings = response.data.bookings
            that.bookings.map(booking => {
              console.log('setting token in local'. booking)
              localStorage.setItem('tokens', JSON.stringify([booking.token]));
            })
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
    initForm() {
        deleteCookie(this.token_label)
        bus.$emit('initialiseForm')
    },
    // remove cookie
    clearForm() {
      this.initForm()
      // init form is an event that may not yet have happend
      this.getBookings(localStorage.active_token)
    },
    storeActiveToken() {
      // localStorage.active_token[this.booking_token] = 'active'
    }
  }
}
</script>
<style scoped lang="scss">
</style>


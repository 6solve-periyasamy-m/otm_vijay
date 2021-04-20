
<template>
    <div>Count {{count}}</div>
</template>
<script>
import Vuex from 'vuex'
window.store = new Vuex.Store({
  state: {
    count: 0
  },
  mutations: {
    increment (state) {
      state.count++
      console.log('increment')
    }
  }
})
export default {
    mounted() {
        console.log('BookingStore mounted')
        
        console.log('store', store)
    },
    data() {
        return {
            count: 0
        }
    },
    computed: {
        count () {
            return store.state.count
        }
    }
}
</script>

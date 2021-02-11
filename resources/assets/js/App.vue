<template>
  <div id="app">
    <div class="overflowwrap">
    <donut-menu @click1="testFunction1" @click2="testFunction2" @click3="testFunction3" @click4="testFunction4"><donut-menu/>
    <transition name="fade" mode="out-in">
    <component v-bind:is="component"></component>
    </transition>
    </div>
    <Background />
  </div>
</template>

<script>
import bubble-background from './components/bubble-background.vue'
import donut-menu from './components/donut-menu.vue'
import tour-menu from './components/tour-menu.vue'
import details-menu from './components/details-menu.vue'
import extras-menu from './components/extras-menu.vue'
import finance-menu from './components/finance-menu.vue'

export default {
  name: 'App',extras-menu
  methods: {
    testFunction1: function (event) {
      this.component = tour-menu
    },
    testFunction2: function (event) {
      this.component = details-menu
    },
    testFunction3: function (event) {
      this.component = extras-menu
    },
    testFunction4: function (event) {
      this.component = finance-menu
    }
  },
  components: {
    bubble-background,
    donut-menu,
    tour-menu,
    details-menu,
    extras-menu,
    finance-menu
  },
  data () {
    return {
      component: ''
    }
  }
}
</script>
<style scoped>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  height: 100vh;
}
#body{
  background-color: rgb(0, 0, 110);
}
.overflowwrap{
  position: absolute;
  display: table;
  overflow: scroll;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease-out;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>

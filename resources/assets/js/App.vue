<template>
  <div id="app">
    <div class="overflowwrap">
    <Donut @click1="testFunction1" @click2="testFunction2" @click3="testFunction3" @click4="testFunction4"/>
    <transition name="fade" mode="out-in">
    <component v-bind:is="component"></component>
    </transition>
    </div>
    <Background />
  </div>
</template>

<script>
import Background from './components/Background.vue'
import Donut from './components/Donut.vue'
import Menu1 from './components/Menu1.vue'
import Menu2 from './components/Menu2.vue'
import Menu3 from './components/Menu3.vue'
import Menu4 from './components/Menu4.vue'

export default {
  name: 'App',
  methods: {
    testFunction1: function (event) {
      this.component = Menu1
    },
    testFunction2: function (event) {
      this.component = Menu2
    },
    testFunction3: function (event) {
      this.component = Menu3
    },
    testFunction4: function (event) {
      this.component = Menu4
    }
  },
  components: {
    Background,
    Donut,
    Menu1,
    Menu2,
    Menu3,
    Menu4
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

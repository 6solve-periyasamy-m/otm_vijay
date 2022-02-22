<template>
<div id="show" class="terms">
<a href="#show" @click="show=!show">{{show?"Hide":"Show"}}</a> | <a href="#show" @click="scroll=!scroll">{{scroll?"No Scroll":"Scroll"}}</a>
<div class="page" v-show="show" :class="{ scrolling:scroll }"> 
<pre>
  {{tour.terms}}
</pre>
</div>
</div>
</template>
<script>
  export default {
    data() {
      return {
        show: false,
        scroll: false
      }
    }
  }
</script>
<style scoped lang="scss">
  .scrolling {
    border: 2px grey inset;
    display: block;
    max-height: 100vh;
    overflow-y: auto;
  }
  .terms {
    width: 360px;
    .page {
      background: #edefed;
      padding: 1rem;
      margin: 0;
      pre {
        width: 320px;
        white-space: pre-wrap;
        word-wrap: break-word;
      }
    }
  }
  @media screen and (min-width: 500px) {
    .terms {
      width: 460px;
      .page {
          padding: 0.25rem; 
          pre {
            width: 420px;
          }
       }
    }
  }
  @media screen and (min-width: 784px) {
    .terms {
      width: 700px;
      .page {
          padding: 0.5rem; 
          pre {
            width: 640px;
          }
       }
    }
  }
  @media screen and (min-width: 992px) {
    .terms {
      width: 880px;
      .page {
          padding: 1rem; 
          pre {
            width: 800px;
          }
       }
    }
  }
  @media screen and (min-width: 1200px) {
    .terms {
      width: 920px;
      .page {
          padding: 1rem; 
          pre {
            width: 880px;
          }
       }
    }
  }
</style>

<template>
    <div class="bookingform-footer">
        <div class="py-2">
            <img class="small-logo logo" :src="logoPath" :alt="logoPath"/>
            <h6 class="bookingform-header__title--main">{{company}}</h6>
            <p class="copyright">&copy; Octopus Travel Matrix Ltd.</p>
        </div>
    </div>
</template>
<script>
export default {
    props: ['logo', 'company'],
    mounted() {
        if (this.logo.substr(0,1) !== '/') {
            this.logoPath = `/${this.logo}`
        } else {
            this.logoPath = this.logo
        }
    },
    data() { 
        return {
            logoPath: ''
        }
    }
}
</script>

<style scoped>
.bookingform-footer {
    padding-left: 1rem;
    padding-right: 1rem;
}
.img-small {
      width: 12vw;
      height: auto;
}
.small-logo {
    width: 150px;
    height: auto;
}
.copyright {
      font-size: 9px;
      text-align: right;
      color: #aaa;
}
</style>

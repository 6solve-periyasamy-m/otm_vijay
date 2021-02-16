<template>
<div class="container">
    <div class="row">
        <div class="bookingform-header">
            <div class="py-2">
                <img class="img-fluid" src="/images/octlogo.png" />
            </div>
            <div>
                <h1 class="tourheader">Octopus Travel Matrix Booking Form</h1>
            </div>
        </div>
    </div>
</div>
</template>
<script>
export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
<style lang="scss" scoped>
.bookingform-header {
    display: flex;
    flex-direction: column;
}
.img-fluid {
    width: 50vw;
    height: auto;
}
</style>

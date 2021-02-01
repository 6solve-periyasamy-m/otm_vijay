<template>
<div class="container">
<div class="row">
    <div class="col-sm-6">
        <div class="py-2">
            <img :src="image"/>
        </div>
    </div>
    <div class="col-sm-6 centercol">
        <div class="text-center">
            <h1 class="tourheader">Octopus Travel Matrix Booking Form</h1>
        </div>
    </div>
</div>
</div>
</template>

<script>
import image from "@/assets/images/octlogo.png"

export default {
        data: function () {
            return {
                image: image
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>


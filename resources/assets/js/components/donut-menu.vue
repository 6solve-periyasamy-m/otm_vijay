<template>
<div class="donutholder">
    <div class="donut">
        <div class="quarter quarter1" id="q1" @click="tourSelect">
            <h3 class="donuttext dt1">Your<br> Tour</h3>
        </div>
        <div class="quarter quarter2" id="q2" @click="$emit('click2')">
            <h3 class="donuttext dt2">Your<br> Details</h3>
        </div>
        <div class="quarter quarter3" id="q3" @click="$emit('click3')">
            <h3 class="donuttext dt3">Your<br> Extras</h3>
        </div>
        <div class="quarter quarter4" id="q4" @click="$emit('click4')">
            <h3 class="donuttext dt4">Your<br> Finance</h3>
        </div>
        <div class="cutout"></div>
    </div>
</div>
</template>
<script>
export default {
    name: 'donut-menu',
    methods: {
      tourSelect(event) {
        alert('tour-select');
         bus.$emit('tourSelected');
      }
    }
}
</script>
<style scoped>
.donutholder {
    display: inline-block;
    width: 100vw;
    justify-content: center;
    z-index: 1;
}

.donut {
    position: relative;
    width: 70vw;
    z-index: 2;
    margin: 5vh auto auto auto;
}

.donut:after {
    content: "";
    display: block;
    padding-bottom: 100%;
}

.quarter {
    position: absolute;
    width: 50%;
    height: 50%;
    transition: background-color 0.5s ease-in-out;
    background-color: rgb(84, 124, 253);
    box-shadow: 0 1vw 2vw 0 rgba(0, 0, 0, 0.2), 0 1vw 1vw 0 rgba(0, 0, 0, 0.19);
}

.quarter:hover {
    background: rgb(0, 14, 139);
}

.quarter1 {
    top: 0;
    left: 0;
    border-radius: 100% 0 0 0;
}

.quarter2 {
    top: 0;
    right: 0;
    background-color: rgb(45, 94, 255);
    border-radius: 0 100% 0 0;
}

.quarter3 {
    bottom: 0;
    left: 0;
    background-color: rgb(45, 94, 255);
    border-radius: 0 0 0 100%;
}

.quarter4 {
    bottom: 0;
    right: 0;
    border-radius: 0 0 100% 0;
}

.donuttext {
    text-align: center;
    margin: 33% 0;
    color: white;
    pointer-events: none;
}

.dt1 {
    padding-top: 5%;
    padding-left: 5%;
}

.dt2 {
    padding-top: 5%;
    padding-right: 5%;
}

.dt3 {
    padding-bottom: 5%;
    padding-left: 5%;
}

.dt4 {
    padding-bottom: 5%;
    padding-right: 5%;
}

.cutout {
    width: 30%;
    height: 30%;
    background: #00b4ff;
    box-shadow: inset 0 1vw 2vw 0 rgba(0, 0, 0, 0.2), 0 1vw 1vw 0 rgba(0, 0, 0, 0.19);
    position: absolute;
    top: 35%;
    left: 35%;
    border-radius: 50%;
    pointer-events: none;
    transition: background-color 0.5s ease-in-out;
}

.donuttext {
    text-align: center;
    margin: 33% 0;
    color: #ffffff;
    pointer-events: none;
    text-shadow: 0px 3px 3px #333333;
    font-family: 'Montserrat', sans-serif;
}

.dt1 {
    padding-top: 5%;
    padding-left: 5%;
}

.dt2 {
    padding-top: 5%;
    padding-right: 5%;
}

.dt3 {
    padding-bottom: 5%;
    padding-left: 5%;
}

.dt4 {
    padding-bottom: 5%;
    padding-right: 5%;
}

h3 {
    font-size: 5vw;
    font-weight: bolder;
}

@media only screen and (min-width: 450px) {
    .donut {
        width: 55vw;
    }

    h3 {
        font-size: 3.5vw;
    }
}

@media only screen and (min-width: 850px) {
    .donut {
        margin-top: 2vh;
        width: 40vw;
    }

    h3 {
        font-size: 3vw;
    }
}
</style>

<template>
    <div id="app-tests" class="box">
        <h1>VueJS with font-awesome, bootstrap and jquery</h1>
        <div class="row">
            <div class="col">
                <p>VueJS renders frontend components, VueJS is working.</p>
                <p><font-awesome-icon icon="user-secret" />Font-awesome is installed.</p>
            </div>
            <div class="col">
                <p>
                    <span class="text-normal">Bootstrap is working</span>,
                    <span class="text-warning">warning</span>,
                    <span class="text-danger">danger</span>
                </p>
                <p class="blackground">This is has a black background, jQuery is changing it to blue</p>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    mounted() {
        console.log('vue is mounted')
        this.checkJquery()
    },
    methods: {
        checkJquery() {
            $('.blackground').addClass('blue')
        }
    }
}
</script>
<style>
.box {
    margin: 1rem 2rem 1rem;
    padding: 1rem;
    border: 1rem grey double;
}
.col {
    border: 1px #ddd dotted;
    margin-left: 1rem;
    margin-right: 1rem;
}
.blackground {
        background: black;
        color: white;
}
.blue {
    background: blue;
}
</style>

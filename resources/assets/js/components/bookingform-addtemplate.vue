<template>
    <div class="container">
        <div class="EPT" id="extraPersonTemplate">
            <div class="row">
                <div class="col-sm-6">
                    <div class="md-form">
                        <label class="form-label">First name</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="md-form">
                        <label class="form-label">Last name</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="md-form">
                        <label class="form-label">E-mail</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="md-form">
                        <label class="form-label">Phone number</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-5">
                    <select class="customdropdown">
                        <option value="" disabled selected>Contact number type</option>
                        <option value="1">Mobile</option>
                        <option value="2">Home</option>
                        <option value="3">Buisness</option>
                    </select>
                </div>
                <div class="col-sm-1"></div>
                <div class="col-sm-6">
                    <div class="md-form">
                        <label class="form-label">Contact number</label>
                        <input type="text" class="form-control">
                    </div>
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

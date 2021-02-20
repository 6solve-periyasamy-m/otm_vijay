<template>
<div class="body">
    <div class="row">
        <div class="col-12">
            <h3>Cut and paste phone numbers for testing</h3>
            <select v-model="pattern_number" @change="setPattern">
                <option>Select</option>
                <option :value="1">UK Numbers</option>
                <option :value="2">Permissive</option>
                <option :value="3">Longest</option>
                <option :value="4">Long</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="show">{{pattern}}</div>
            <textarea v-model="phonenumbers" class="bigbox" rows="20" cols="80">
            </textarea>
        </div>
        <div class="col-6">
            <button @click="checklist">check</button>
            <div class="bigbox">
                <div class="row" v-for="valid in validations" :key="valid.num">
                    <div class="col-8">{{valid.num}}</div>
                    <div class="col-4"><p :class="{red: !valid.valid}">{{valid.valid}}</p></div>
                </div>
            </div>
        </div>
    </div>
        <a href='https://www.bestrandoms.com/random-uk-phone-number' target="_blank">Random Phone numbers for a country</a>
</div>
</template>
<script>
export default {
    data() {
        return {
            phonenumbers: `070 8029 3854 
070 0259 4344 
07565 112 990
07 5651 12990
08989
798437293487298347293479234
078 3922 1816 
077 4016 6439 
079 1615 4696 
070 4814 1677 
079 1959 4259 
078 3061 9492 
078 0915 2147 
070 4918 7411 
077 2259 5709 
070 6168 3189 
070 2963 4505 
077 3928 4135 
077 2213 8843 
077 6628 7062 
077 2231 8010 
070 0170 3318 
079 2203 5107
079 7372 1624 
078 8371 9007 
070 5177 5046 
077 2797 6004 
079 7492 4053 
070 8855 5976 
078 0848 8080 
070 2852 1724 
078 1900 0967 
077 6292 1700 
070 6652 1480 
070 5261 3551 
079 5320 6666 
079 0891 5790 
077 1533 6124 
077 3649 8369 
079 2391 8188 
078 5224 0575 
077 3126 7278 
079 7092 3570`,
            pattern_number: null,
            validations: [],
            pattern: null
        }
    },
    methods: {
        validationPatterns(num) {
            const patterns = [
            /^\s*((?:[+](?:\s?\d)(?:[-\s]?\d)|0)?(?:\s?\d)(?:[-\s]?\d){9}|[(](?:\s?\d)(?:[-\s]?\d)+\s*[)](?:[-\s]?\d)+)\s*$/,
            /((\+44(\s\(0\)\s|\s0\s|\s)?)|0)7\d{3}(\s)?\d{6}/,
            /^((((\(?0\d{4}\)?\s?\d{3}\s?\d{3})|(\(?0\d{3}\)?\s?\d{3}\s?\d{4})|(\(?0\d{2}\)?\s?\d{4}\s?\d{4}))(\s?\(\d{4}|\d{3}))?)|((\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3})|((((\+44\s?\d{4}|\(?0\d{4}\)?)\s?\d{3}\s?\d{3})|((\+44\s?\d{3}|\(?0\d{3}\)?)\s?\d{3}\s?\d{4})|((\+44\s?\d{2}|\(?0\d{2}\)?)\s?\d{4}\s?\d{4}))(\s?\(\d{4}|\d{3}))?$/,
            /(^0[1-9]\d{1}\s\d{4}\s?\d{4}$)|(^0[1-9]\d{2}\s\d{3}\s?\d{4}$)|(^0[1-9]\d{2}\s\d{4}\s?\d{3}$)|(^0[1-9]\d{3}\s\d{3}\s?\d{2}$)|(^0[1-9]\d{3}\s\d{3}\s?\d{3}$)|(^0[1-9]\d{4}\s\d{3}\s?\d{2}$)|(^0[1-9]\d{4}\s\d{2}\s?\d{3}$)|(^0[1-9]\d{4}\s\d{2}\s?\d{2}$)/
            ]
            return patterns[num]
        },
        setPattern() {
            this.pattern = this.validationPatterns(this.pattern_number - 1)
        },
        checkPhoneNumberValid(num) {
            const usePattern = this.pattern; //this.getPattern(num) //this.validationPatterns(this.pattern_number)
            return usePattern.test(num)
        },
        checklist() {
            const list = this.phonenumbers.split("\n")
            var self = this
            this.validations = list.map(function(num) {
                const valid = self.checkPhoneNumberValid(num)
                return { num: num, valid: valid}
            })
        }
    }
}
</script>
<style scoped>
.body {
    margin: 1rem;
    padding: 2rem;
}
.red {
    color: white;
}
.bigbox {
    border: 1px navy inset;
    width: 100%;
    height: auto;
    padding: 3px;
    margin: 1rem;
}
.show {
    border: 1px navy outset;
    padding: 3px;
    margin: 1rem;
}
</style>

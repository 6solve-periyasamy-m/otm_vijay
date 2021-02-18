<template>
    <div>
        <h3>Cut and paste phone numbers for testing</h3>
        <textarea v-model="phonenumbers" rows="20" cols="80">
        </textarea>
        <button @click="checklist">check</button>
        <div v-for="valid in validations" :key="valid.num">
            {{valid.num}} {{valid.valid}}
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            phonenumbers: '',
            validations: []
        }
    },
    methods: {
        validPhone(num) {

            const valid_uk = /^\s*((?:[+](?:\s?\d)(?:[-\s]?\d)|0)?(?:\s?\d)(?:[-\s]?\d){9}|[(](?:\s?\d)(?:[-\s]?\d)+\s*[)](?:[-\s]?\d)+)\s*$/
            const valid_uk2 = /((\+44(\s\(0\)\s|\s0\s|\s)?)|0)7\d{3}(\s)?\d{6}/
            // valid_uk appears to be fairly accurate
            const valid_uk3 = /^((((\(?0\d{4}\)?\s?\d{3}\s?\d{3})|(\(?0\d{3}\)?\s?\d{3}\s?\d{4})|(\(?0\d{2}\)?\s?\d{4}\s?\d{4}))(\s?\(\d{4}|\d{3}))?)|((\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3})|((((\+44\s?\d{4}|\(?0\d{4}\)?)\s?\d{3}\s?\d{3})|((\+44\s?\d{3}|\(?0\d{3}\)?)\s?\d{3}\s?\d{4})|((\+44\s?\d{2}|\(?0\d{2}\)?)\s?\d{4}\s?\d{4}))(\s?\(\d{4}|\d{3}))?$/
            const valid_uk4 = /(^0[1-9]\d{1}\s\d{4}\s?\d{4}$)|(^0[1-9]\d{2}\s\d{3}\s?\d{4}$)|(^0[1-9]\d{2}\s\d{4}\s?\d{3}$)|(^0[1-9]\d{3}\s\d{3}\s?\d{2}$)|(^0[1-9]\d{3}\s\d{3}\s?\d{3}$)|(^0[1-9]\d{4}\s\d{3}\s?\d{2}$)|(^0[1-9]\d{4}\s\d{2}\s?\d{3}$)|(^0[1-9]\d{4}\s\d{2}\s?\d{2}$)/
            // /\s*\(?0\d{4}\)?(\s*|-)\d{3}(\s*|-)\d{3}\s*)|(\s*\(?0\d{3}\)?(\s*|-)\d{3}(\s*|-)\d{4}\s*)|(\s*(7|8)(\d{7}|\d{3}(\-|\s{1})\d{4})\s*/
            return valid_uk.test(num)
            return num.match(valid_uk)
        },
        checklist() {
            const list = this.phonenumbers.split("\n")
            var self=this
            this.validations = list.map(function(num) {
                // const valid_uk = /^((((\(?0\d{4}\)?\s?\d{3}\s?\d{3})|(\(?0\d{3}\)?\s?\d{3}\s?\d{4})|(\(?0\d{2}\)?\s?\d{4}\s?\d{4}))(\s?\(\d{4}|\d{3}))?)|((\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3})|((((\+44\s?\d{4}|\(?0\d{4}\)?)\s?\d{3}\s?\d{3})|((\+44\s?\d{3}|\(?0\d{3}\)?)\s?\d{3}\s?\d{4})|((\+44\s?\d{2}|\(?0\d{2}\)?)\s?\d{4}\s?\d{4}))(\s?\(\d{4}|\d{3}))?$/
                // const valid = num.match(valid_uk)
                const valid = self.validPhone(num)
                return { num: num, valid: valid}
                //this.validations.push(valid)
            })
        }
    }
}
</script>

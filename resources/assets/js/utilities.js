/**
 * utilities 
 * simple module.functions
 * dates
 */

var num = {
    pad(num, size) {
        var s = "000000000" + num;

        return s.substr(s.length-size)
    },
}
var dates = {
    makeDateFromString(s) {
        // console.debug(s)
        if (typeof(s) === 'undefined' || s.length < 6) {
            return 'invalid date'
        }
        const ds = s.split('-')
        return num.pad(parseInt(ds[2]),2) + '/' + num.pad(parseInt(ds[1]),2) + '/' +  parseInt(ds[0])
    },
    showTimesFromDateTime(s) {
        const ds = s.split(':')
        return num.pad(parseInt(ds[2]),2) + ':' + num.pad(parseInt(ds[1]),2)
    },
    bookingTime(s) {
        const datePart = dates.makeDateFromString(s) + ' ' + dates.showTimesFromDateTime(s)
        return datePart
    }
}

export default dates
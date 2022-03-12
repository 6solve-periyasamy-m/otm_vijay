/**
 * utilities 
 * simple module.functions
 * dates
 */

let num = {
    pad(num, size) {
        var s = "000000000" + num;

        return s.substr(s.length-size)
    },
}

let dates = {
    isoString(ds) {
        if (ds != undefined && ds != null) {
            // console.log('isoString:', ds);
            return new Date(ds).toISOString().substring(0, 10)
        }
    },
    makeDateFromString(s) {
        if (typeof(s) === 'undefined' || s.length < 6) {
            return 'invalid date'
        }
        const datepart = s.split(' ')
        const ds = datepart[0].split('-')
        return num.pad(parseInt(ds[2]),2) + '/' + num.pad(parseInt(ds[1]),2) + '/' +  parseInt(ds[0])
    },
    showTimesFromDateTime(s) {
        const timepart = s.split(' ')
        const ts = timepart[1].split(':')
        return num.pad(parseInt(ts[0]),2) + ':' + num.pad(parseInt(ts[1]),2)
    },
    bookingTime(s) {
        const datePart = dates.makeDateFromString(s) + ' ' + dates.showTimesFromDateTime(s)
        return datePart
    }
}

export default dates

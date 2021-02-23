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
        const ds = s.split('-')
        return num.pad(parseInt(ds[2]),2) + '/' + num.pad(parseInt(ds[1]),2) + '/' +  parseInt(ds[0])
    }
}

export default dates
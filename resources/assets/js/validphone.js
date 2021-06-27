
validPhone(phone) {
    const valid_uk = /^\s*((?:[+](?:\s?\d)(?:[-\s]?\d)|0)?(?:\s?\d)(?:[-\s]?\d){9}|[(](?:\s?\d)(?:[-\s]?\d)+\s*[)](?:[-\s]?\d)+)\s*$/
    return valid_uk.test(phone)
}

export default validPhone

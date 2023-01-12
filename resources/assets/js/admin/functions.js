function __build(properties) {
    return function (token, item) {
        return (item % 2) ? properties[token] : token;
    };
}

function render(template, properties) {
    return template.map(__build(properties)).join('');
}

function template(name) {
    return $(`script[data-template='${name}']`).text().trim().split(/\$\{(.+?)}/g)
}

function swapButton(btn, field) {
    let button = $(btn);
    let checkbox = $(`input[type=checkbox][name="${field}"]`);
    let checked = !(checkbox.prop('checked'));
    checkbox.prop('checked', checked);
    flipButton(button, checked);
}
function verifyCheckButton(btn, field) {
    console.log("Verifying Button...")
    flipButton($(btn), $(`input[type=checkbox][name="${field}"]`).prop('checked'));
}
function flipButton(btn, state) {
    if (state) {
        console.log('On');
        btn.removeClass('btn-danger');
        btn.addClass('btn-success');
    } else {
        console.log('Off');
        btn.removeClass('btn-success');
        btn.addClass('btn-danger');
    }
}
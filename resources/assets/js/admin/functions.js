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

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
        btn.removeClass('cross-out');
        btn.addClass('btn-success');
    } else {
        console.log('Off');
        btn.removeClass('btn-success');
        btn.addClass('btn-danger');
        btn.addClass('cross-out');
    }
}

// Function to toggle sidebar and initialize state
function toggleSidebar() {
    let sidebar = $(".otm-sidebar");
    let arrow = $(".sidebar-arrow");

    // Toggle the 'shown' class on the sidebar
    sidebar.toggleClass('shown');

    // Toggle the classes on the arrow
    arrow.toggleClass('fa-solid fa-bars fa-solid fa-xmark');

    // Store the state of the sidebar in localStorage
    localStorage.setItem('isSidebarShown', sidebar.hasClass('shown'));
}

// Initialize sidebar state on page load
$(document).ready(function() {
    let sidebar = $(".otm-sidebar");
    let arrow = $(".sidebar-arrow");

    // Retrieve the state of the sidebar from localStorage
    let isSidebarShown = localStorage.getItem('isSidebarShown') === 'true';

    // Update the sidebar, arrow, and icon based on the stored state
    sidebar.toggleClass('shown', isSidebarShown);
    arrow.toggleClass('fa-solid fa-bars', !isSidebarShown);
    arrow.toggleClass('fa-solid fa-xmark', isSidebarShown);
});
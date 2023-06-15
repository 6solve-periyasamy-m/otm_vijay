<div class="toast" id="${id}" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header border-bottom border-2 border-${color}">
        <strong class="me-auto">${title}</strong>
        <button type="button" class="btn-close" data-dismiss="toast" aria-label="Close" onclick="$(this).closest('.toast').remove()"></button>
    </div>
    <div class="toast-body">
        ${body}
    </div>
</div>

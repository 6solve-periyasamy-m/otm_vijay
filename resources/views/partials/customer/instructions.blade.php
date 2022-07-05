@if(!(flag('payment.required', true)))
    <span>Click <span class="btn btn-success">+</span> to add to order for payment later, or <span
                class="btn btn-primary">$</span> to buy now</span>
@else
    <span>Click <span class="btn btn-primary">$</span> to buy now</span>
@endif

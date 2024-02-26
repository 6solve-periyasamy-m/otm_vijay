<div>
    @if(!$this->accepted)
        <x-customer.accordion id="cost-collapse" nobg>
            <x-slot:header>
                <h2 class="mb-0" style="width: 100%; text-align: center;">Terms and Conditions</h2>
            </x-slot:header>
            {!! $this->booking->tour->terms !!}
            <br />
            <button wire:click="accept" class="btn btn-success">Accept the Terms and Conditions</button>
        </x-customer.accordion>
    @else
        <x-admin.section.card>
            <h2 class="mb-0" style="width: 100%; text-align: center;">Make Payment</h2>
        </x-admin.section.card>
        @if(\Gateway::getDefaultGateway() !== null)
            <div class="hidden">
                <x-admin.section.card>
                    <div class="form-material">
                        <div class="form-material row">
                            <x-customer.input wire:model="amount" width="10" required>
                                How much do you want to pay today?
                            </x-customer.input>
                            <div class="form-group col-12 col-xl-2" style="padding-top: 19px;">
                                <button wire:click="pay" class="btn btn-primary text-white">Make Payment</button>
                            </div>
                        </div>
                    </div>
                </x-admin.section.card>
            </div>
        @else
            <div class="hidden">
                <x-admin.section.card>
                    <h2 class="col-md-12 mb-0">The operator has not enabled online payments</h2>
                </x-admin.section.card>
            </div>
        @endif
    @endif
    <x-wire-loader />
</div>

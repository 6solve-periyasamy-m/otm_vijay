<?php

namespace App\Http\Livewire\Admin\Quote;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\AdditionalCost;
use App\Models\Customer\Agent;
use App\Models\Customer\Organization;
use App\Models\Quote\Quote;
use App\Models\Quote\QuotePricePoint;
use App\Models\Quote\QuoteProspect;
use App\Models\System\LargeTextTemplate;
use Carbon\Carbon;
use Livewire\Component;

class Form extends Component
{
    use LivewireForm, SendsEvents;

    public Quote|int|null $quote;
    public QuoteProspect|null $prospect = null;
    public float|null $price = null;
    public int|null $footerTemplate = null;
    public int|null $termsTemplate = null;
    public int|null $paymentTemplate = null;
    public $minToDate;
    public $maxFinalDate;
    /** @var array<array{id: int|null, name: string, per_customer: boolean, amount: float}> */
    public array $costs = [];
    public bool $agentRequired = false;

    public function mount(Quote|int|null $quote): void
    {
        $this->quote = Quote::getForMount($quote);
        $this->prospect = $quote->leadTraveller ?? new QuoteProspect();
        if ($this->quote->id !== null) {
            $this->price = $this->quote->pricePoints()->where('quantity', '=', 1)->first()?->price_per_person;
        }
        $this->quote->expires = $this->quote->expires ?? now()->addDays((int)setting('system.quote.expiry', null));
        $this->prospect->travelling = $this->prospect->travelling ?? true;
        $this->prospect->paying = $this->prospect->paying ?? true;
        $this->agentRequired = $this->quote->agent_id ? true : false;
        if ($this->quote->date_from) {
            $this->minToDate = Carbon::parse($this->quote->date_from)->subDay()->toDateString();
            $this->maxFinalDate = Carbon::parse($this->quote->date_from)->subDay()->toDateString();
        }

        foreach ($this->quote->costs as $cost) {
            $this->costs[] = [
                'id' => $cost->id,
                'name' => $cost->name,
                'amount' => $cost->amount,
                'per_customer' => $cost->per_customer,
            ];
        }
    }

    public function changedQuoteDateFrom($value): void
    {
        if ($value) {
            $dateFrom = Carbon::parse($value);
            $today = Carbon::today();
            $this->quote->date_to = $value;
            $this->minToDate = $dateFrom->isAfter($today) ? $dateFrom->toDateString() : $today->toDateString();
            $this->maxFinalDate = $dateFrom->subDay()->toDateString();
        }
    }

    public function changedQuoteOrganizationId($organization_id): void
    {
        if ($organization_id) {
            $organization = Organization::find($organization_id);
            if ($organization) {
                $this->quote->commission = $organization->commission;
            }
        } else {
            $this->quote->commission = null;
            $this->quote->agent_id = null;
        }
        $this->validateOnly('quote.agent_id');
    }

    public function updated($field, $value): void
    {
        match ($field) {
            'quote.date_from' => $this->changedQuoteDateFrom($value),
            'quote.organization_id' => $this->changedQuoteOrganizationId($value),
            default => null,
        };
    }

    public function save()
    {
        $this->validate();
        if ($this->quote->commission != 0 && empty($this->quote->commission)) { $this->quote->commission = null; }
        if ($this->quote->brand_id <= 0) { $this->quote->brand_id = null; }
        $this->quote->brand_id = $this->quote->brand_id ?? null;
        $this->quote->currency_id = $this->quote->currency_id ?? null;
        $this->quote->is_deposit_percentage = $this->quote->is_deposit_percentage ?? false;
        $this->prospect->travelling = $this->prospect->travelling ?? false;
        $this->prospect->paying = $this->prospect->paying ?? false;
        $this->prospect->save();
        $this->quote->lead_traveller_id = $this->prospect->id;
        $this->quote->save();
        $this->prospect->quote_id = $this->quote->id;
        $this->prospect->save();
        $this->quote->reference = $this->quote->reference ?? $this->quote->repository->generateReference();
        $this->quote->invoice_footer = $this->quote->invoice_footer ?? "";
        $this->quote->save();

        $pricePoint = $this->quote->pricePoints()->where('quantity', '=', 1)->first() ?? QuotePricePoint::make(['quantity' => 1,]);
        $pricePoint->price_per_person = $this->price;
        $this->quote->pricePoints()->save($pricePoint);

        foreach ($this->costs as $cost) {
            $model = AdditionalCost::find($cost['id'] ?? null);
            if ($model !== null) {
                $model->name = $cost['name'];
                $model->amount = $cost['amount'];
                $model->per_customer = $cost['per_customer'];
                $model->save();
            } else {
                $model = new AdditionalCost([
                    'name' => $cost['name'],
                    'amount' => $cost['amount'],
                    'per_customer' => $cost['per_customer'],
                ]);
                $this->quote->costs()->save($model);
            }

        }

        return redirect()->route('quotes.view', ['quote' => $this->quote]);
    }

    public function inputChanged(string|null $key = null)
    {
        if ($key === 'quote.organization_id') {
            $this->quote->commission = $this->quote->organization?->commission ?? $this->quote->commission;
            $this->quote->agents = Agent::where('organization_id', $this->quote->organization?->id)->get();
        }

        if ($key === 'quote.commission') {
            /** @noinspection NestedPositiveIfStatementsInspection */
            if ($this->quote->commission !== 0 && empty($this->quote->commission)) {
                $this->quote->commission = null;
            }
        }
        if ($key === 'termsTemplate') {
            $template = LargeTextTemplate::find($this->termsTemplate);
            if ($template === null) { return; }
            $this->quote->terms = $template->content;
            $this->updateValue('quote.terms', $template->content);
        }
        if ($key === 'footerTemplate') {
            $template = LargeTextTemplate::find($this->footerTemplate);
            if ($template === null) { return; }
            $this->quote->invoice_footer = $template->content;
            $this->updateValue('quote.invoice_footer', $template->content);
        }
        if ($key === 'paymentTemplate') {
            $template = LargeTextTemplate::find($this->paymentTemplate);
            if ($template === null) { return; }
            $this->quote->payment_details = $template->content;
            $this->updateValue('quote.payment_details', $template->content);
        }
        if ($key === 'price') {
            $roundValue = (float)setting('round.base_price', null);
            if ($roundValue > 0) {
                $new = round_to_nearest($this->price, $roundValue);
                if ($this->price !== $new) {
                    $this->price = $new;
                    if (empty($this->price)) { $this->price = null; }
                    $this->toast('Base Price Rounded', "Rounded base price to nearest $roundValue", 'primary');
                }
            }
        }
    }

    public static function getSelectAgencies($organization_id)
    {
        if ($organization_id == 0) return null;
        $agents = Agent::where('organization_id', '=', $organization_id)->get();
        $data = [];
        foreach ($agents as $agent) {
            $option = [];
            $option['id'] = $agent->id;
            $option['text'] = $agent->first_name . ' ' . $agent->last_name;
            $data['results'][] = $option;
        }
        return $data;
    }

    public function addCost()
    {
        $this->costs[] = [
            'id' => null,
            'name' => null,
            'amount' => null,
            'per_customer' => false,
        ];
    }

    public function removeCost($key)
    {
        if (array_key_exists($key, $this->costs)) {
            if ($this->costs[$key]['id'] !== null) {
                $this->quote->costs()->where('id', $this->costs[$key]['id'])->forceDelete();
            }
            unset($this->costs[$key]);
        }
    }

    public function render()
    {
        return view('livewire.admin.quote.form');
    }

    public function rules(): array
    {
        return [
            'quote.name' => 'required|string|min:3',
            'quote.brand_id' => 'nullable|integer',
            'quote.tax_bracket_id' => 'nullable|integer|exists:tax_brackets,id',
            'quote.currency_id' => 'nullable|integer|exists:currencies,id',
            'quote.consultant_id' => 'nullable|integer|exists:users,id',
            'quote.organization_id' => 'nullable|integer|exists:organizations,id',
            'quote.agent_id' => $this->agentRequired
                ? 'required|integer|exists:agents,id'
                : 'nullable|integer|exists:agents,id',
            'quote.event_id' => 'nullable|integer|exists:events,id',
            'quote.commission' => 'nullable|numeric|between:0,100',
            'quote.deposit' => 'nullable|numeric',
            'quote.is_deposit_percentage' => 'nullable|boolean',
            'price' => 'required|numeric|gte:0|regex:/^[0-9]+(\.[0-9]{1,2})?$/',
            'quote.single_occupancy_surcharge' => 'required|numeric|gte:0',
            'quote.description' => 'nullable|string|min:3',
            'prospect.customer_id' => 'required|integer|exists:customers,id',
            'prospect.paying' => 'nullable|boolean',
            'prospect.travelling' => 'nullable|boolean',
            'quote.date_from' => 'required|date',
            'quote.date_to' => 'required|date|after:quote.date_from',
            'quote.final_payment' => 'required|date|before_or_equal:quote.date_from',
            'quote.expires' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if ($this->quote['date_from'] && strtotime($value) > strtotime($this->quote['date_from'])) {
                        $fail('The expiration date must be before date from.');
                    }
                }
            ],
            'quote.internal_notes' => 'nullable|string|min:3',
            'quote.external_notes' => 'nullable|string|min:3',
            'quote.terms' => 'required|string|min:3',
            'quote.invoice_footer' => 'nullable|string|min:3',
            'quote.payment_details' => 'nullable|string|min:3',
        ];
    }
}

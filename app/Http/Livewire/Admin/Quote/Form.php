<?php

namespace App\Http\Livewire\Admin\Quote;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Quote\Quote;
use App\Models\Quote\QuotePricePoint;
use App\Models\Quote\QuoteProspect;
use App\Models\System\LargeTextTemplate;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;

class Form extends Component
{
    use LivewireForm, SendsEvents;

    public Quote|int|null $quote;
    public QuoteProspect|null $prospect = null;
    public QuotePricePoint|null $pricePoint = null;
    public int|null $footerTemplate = null;
    public int|null $termsTemplate = null;

    public function mount(Quote|int|null $quote): void
    {
        $this->quote = Quote::getForMount($quote);
        $this->prospect = $quote->leadTraveller ?? new QuoteProspect();
        if ($this->quote->id !== null) {
            $this->pricePoint = $this->quote->pricePoints()->where('quantity', '=', 1)->first();
        }
        if ($this->pricePoint === null) {
            $this->pricePoint = new QuotePricePoint(['quantity' => 1, 'price_per_person' => 0,]);
        }
        $this->quote->expires = $this->quote->expires ?? now()->addDays(setting('system.quote.expiry', null));
    }

    public function save()
    {
        $this->validate();
        if ($this->quote->commission != 0 && empty($this->quote->commission)) { $this->quote->commission = null; }
        if ($this->quote->brand_id <= 0) { $this->quote->brand_id = null; }
        $this->quote->brand_id = $this->quote->brand_id ?? null;
        $this->quote->is_deposit_percentage = $this->quote->is_deposit_percentage ?? false;
        $this->prospect->travelling = $this->prospect->travelling ?? false;
        $this->prospect->paying = $this->prospect->paying ?? false;
        $this->prospect->save();
        $this->quote->lead_traveller_id = $this->prospect->id;
        $this->quote->save();
        $this->quote->reference = $this->quote->reference ?? $this->quote->repository->generateReference();
        $this->quote->save();
        $this->pricePoint->quote_id = $this->quote->id;
        $this->pricePoint->save();
        return redirect()->route('quotes.view', ['quote' => $this->quote]);
    }

    public function inputChanged(string|null $key = null)
    {
        if ($key === 'quote.organization_id') {
            $this->quote->commission = $this->quote->organization?->commission ?? $this->quote->commission;
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
            'quote.consultant_id' => 'nullable|integer|exists:users,id',
            'quote.organization_id' => 'nullable|integer|exists:organizations,id',
            'quote.event_id' => 'nullable|integer|exists:events,id',
            'quote.commission' => 'nullable|numeric|between:0,100',
            'quote.deposit' => 'nullable|numeric',
            'quote.is_deposit_percentage' => 'nullable|boolean',
            'pricePoint.price_per_person' => 'required|numeric|gte:0',
            'quote.single_occupancy_surcharge' => 'required|numeric|gte:0',
            'quote.description' => 'nullable|string|min:3',
            'prospect.customer_id' => 'required|integer|exists:customers,id',
            'prospect.paying' => 'nullable|boolean',
            'prospect.travelling' => 'nullable|boolean',
            'quote.date_from' => 'required|date',
            'quote.date_to' => 'required|date|after:quote.date_from',
            'quote.final_payment' => 'required|date',
            'quote.expires' => 'required|date',
            'quote.internal_notes' => 'nullable|string|min:3',
            'quote.external_notes' => 'nullable|string|min:3',
            'quote.terms' => 'required|string|min:3',
            'quote.invoice_footer' => 'nullable|string|min:3',
        ];
    }
}

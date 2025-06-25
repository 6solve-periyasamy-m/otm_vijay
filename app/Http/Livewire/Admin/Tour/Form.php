<?php

namespace App\Http\Livewire\Admin\Tour;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\AdditionalCost;
use App\Models\Helper\Enum\LargeTextType;
use App\Models\System\LargeTextTemplate;
use App\Models\Tour\Event;
use App\Models\Tour\Tour;
use Livewire\Component;
use Carbon\Carbon;

class Form extends Component
{
    use SendsEvents;
    use LivewireForm;

    public $manuallySet = [];
    /** @var Tour */
    public Tour|int|null $tour = null;
    public int|null $termsTemplate = null;
    public int|null $footerTemplate = null;
    public int|null $paymentTemplate = null;
    /** @var array<array{id: int|null, name: string, per_customer: boolean, amount: float}> */
    public array $costs = [];
    public $minEndDate;
    public $maxFinalDate;

    public function mount(Tour|int|null $tour = null): void
    {
        if (is_int($tour)) { $tour = Tour::find($tour); }
        if ($tour === null) {
            $footer = LargeTextTemplate::where('default', '=', true)->where('type', '=', LargeTextType::INVOICE_FOOTER)->first();
            $terms = LargeTextTemplate::where('default', '=', true)->where('type', '=', LargeTextType::TERMS)->first();
            $tour = new Tour([
                'invoice_footer' => $footer?->content,
                'terms' => $terms?->content,
            ]);
            $this->termsTemplate = $terms?->id;
            $this->footerTemplate = $footer?->id;
            if (setting('system.installments.deposit', null) !== null) {
                $tour->deposit = setting('system.installments.deposit', null);
                $tour->is_deposit_percentage = true;
            }
        }
        $this->tour = $tour;

        foreach ($this->tour->costs as $cost) {
            $this->costs[] = [
                'id' => $cost->id,
                'name' => $cost->name,
                'amount' => $cost->amount,
                'per_customer' => $cost->per_customer,
            ];
        }

        if ($this->tour->date_from !== null) {
            $this->manuallySet('tour.date_from');
            $this->minEndDate = Carbon::parse($this->tour->date_from)->toDateString();
            $this->maxFinalDate = Carbon::parse($this->tour->date_from)->subDay()->toDateString();
        }
        if ($this->tour->date_to !== null) $this->manuallySet('tour.date_to');
        if ($this->tour->final_payment !== null) $this->manuallySet('tour.final_payment');
    }


    public function inputChanged(string|null $key = null): void
    {
        $this->manuallySet($key);
        match ($key) {
            default => function () {
            },
            "tour.event_id" => $this->eventChanged(),
            "tour.brand_id" => function () {
                if ($this->tour->brand_id === -1) $this->tour->brand_id = null;
            },
            "tour.atol_protection" => function () {
                $this->tour->atol_protected = $this->tour->atol_protected === -1
                    ? null : $this->tour->atol_protected;
            },
            'termsTemplate' => $this->refreshTermsTemplate(),
            'footerTemplate' => $this->refreshFooterTemplate(),
            'paymentTemplate' => $this->refreshPaymentDetailsTemplate(),
            'tour.base_price_per_person' => $this->updateBasePrice(),
            "tour.date_from" => $this->handleStartDateChange(),
        };
    }

    public function updateBasePrice(): void
    {
        $roundValue = (float)setting('round.base_price', null);
        if ($roundValue > 0) {
            $new = round_to_nearest($this->tour->base_price_per_person, $roundValue);
            if ($this->tour->base_price_per_person !== $new) {
                $this->tour->base_price_per_person = $new;
                if (empty($this->tour->base_price_per_person)) { $this->tour->base_price_per_person = null; }
                $this->toast('Base Price Rounded', "Rounded base price to nearest $roundValue", 'primary');
            }
        }
    }

    public function handleStartDateChange()
    {
        if ($this->tour->event_id !== null) return;
        if ($this->tour->date_from) {
            $startDate = Carbon::parse($this->tour->date_from);
            $today = Carbon::today();
            $this->minEndDate = $startDate->isAfter($today) ? $startDate->toDateString() : $today->toDateString();
            $this->tour->date_to = $this->tour->date_from;
            $this->maxFinalDate = $startDate->subDay()->toDateString();
        }
    }

    public function manuallySet($key): void
    {
        if (in_array($key, $this->manuallySet)) return;
        $this->manuallySet[] = $key;
    }

    private function eventChanged(): void
    {
        if (in_array("tour.date_from", $this->manuallySet) || in_array("tour.date_to", $this->manuallySet)) { return; }
        $event = Event::find($this->tour->event_id);
        $this->tour->date_from = $event?->starts_at;
        $this->tour->date_to = $event?->ends_at;
        $this->tour->brand_id = $event?->brand_id;
        $this->updateValue('tour.brand_id', $event?->brand_id);
        $this->dateFromChanged();
        $this->minEndDate = $event?->starts_at->toDateString();
    }

    private function dateFromChanged(): void
    {
        if (in_array("tour.final_payment", $this->manuallySet)) return;
        $final = setting('system.installments.final', null);
        if ($final !== null) {
            $this->tour->final_payment = $this->tour->date_from->subDays($final);
            $this->maxFinalDate = $this->tour->date_from->subDays($final)->toDateString();
        }
    }

    public function render()
    {
        return view('livewire.admin.tour.form');
    }

    public function prepareForSaving(): void
    {
        if ($this->tour->brand_id <= 0) { $this->tour->brand_id = null; }
        $this->tour->is_active = $this->tour->is_active ?? false;
        $this->tour->is_deposit_percentage = $this->tour->is_deposit_percentage ?? false;
        $this->tour->booking_fee = $this->tour->booking_fee ?? 0.0;
        $this->tour->atol_protected = $this->tour->atol_protected === -1 ? null : $this->tour->atol_protected;
        $this->tour->stock_control_active = $this->tour->stock_control_active ?? false;
        $this->tour->accommodation_stock_control = $this->tour->accommodation_stock_control ?? false;
        $this->tour->activity_stock_control = $this->tour->activity_stock_control ?? false;
        $this->tour->flight_stock_control = $this->tour->flight_stock_control ?? false;
        $this->tour->transport_stock_control = $this->tour->transport_stock_control ?? false;
        $this->tour->merchandise_stock_control = $this->tour->merchandise_stock_control ?? false;
        $this->tour->booking_form_url = trim($this->tour->booking_form_url);
        $this->tour->booking_form_url = empty($this->tour->booking_form_url) ? null : $this->tour->booking_form_url;
    }

    public function save(): void
    {
        $this->prepareForSaving();
        $this->validate();
        $create = $this->tour->id === null;
        $this->tour->save();
        if ($create) { $this->tour->repository->cloneFromDefaultInstallments(); }
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
                $this->tour->costs()->save($model);
            }

        }
        $this->redirect(route('tours.view', ['tour' => $this->tour,]));
    }

    public function rules(): array
    {
        return [
            'tour.event_id' => 'nullable|int|exists:events,id',
            'tour.name' => 'required|string|min:3',
            'tour.package_name' => 'nullable|string|min:3',
            'tour.brand_id' => 'nullable|int',
            'tour.tax_bracket_id' => 'nullable|int',
            'tour.description' => 'nullable|string',
            'tour.atol_protected' => 'nullable|int',
            'tour.tour_category_id' => 'nullable|int',
            'tour.is_active' => 'nullable|boolean',
            'tour.booking_form_url' => 'nullable|required_if:tour.is_active,true|string',
            'tour.final_payment' => 'required|date|before_or_equal:quote.date_from|date_format:Y-m-d',
            'tour.date_from' => 'required|date|date_format:Y-m-d',
            'tour.date_to' => 'required|date|after:quote.date_from|date_format:Y-m-d',
            'tour.base_price_per_person' => 'required|numeric|gte:0',
            'tour.deposit' => 'nullable|numeric|gte:0',
            'tour.is_deposit_percentage' => 'nullable|boolean',
            'tour.booking_fee' => 'nullable|numeric|gte:0',
            'tour.single_occupancy_surcharge' => 'nullable|numeric|gte:0',
            'tour.stock' => 'nullable|required_if:tour.stock_control_active,true|integer|gte:0',
            'tour.stock_control_active' => 'nullable|boolean',
            'tour.accommodation_stock_control' => 'nullable|boolean',
            'tour.activity_stock_control' => 'nullable|boolean',
            'tour.flight_stock_control' => 'nullable|boolean',
            'tour.transport_stock_control' => 'nullable|boolean',
            'tour.merchandise_stock_control' => 'nullable|boolean',
            'tour.terms' => 'required',
            'tour.invoice_footer' => 'nullable',
            'tour.notes' => 'nullable',
            'tour.city' => 'nullable|string',
            'tour.country_id' => 'nullable|exists:countries,id',
            'tour.payment_details' => 'nullable|string|min:3',
            'costs.*.id' => 'nullable|int|exists:additional_costs,id',
            'costs.*.name' => 'required|string|min:3',
            'costs.*.amount' => 'required|numeric',
            'costs.*.per_customer' => 'boolean',
        ];
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
                $this->tour->costs()->where('id', $this->costs[$key]['id'])->forceDelete();
            }
            unset($this->costs[$key]);
        }
    }

    private function refreshTermsTemplate(): void
    {
        $template = LargeTextTemplate::find($this->termsTemplate);
        if ($template !== null) {
            $this->tour->terms = $template->content;
            $this->updateValue('tour.terms', $template->content);
        }
    }

    private function refreshFooterTemplate(): void
    {
        $template = LargeTextTemplate::find($this->footerTemplate);
        if ($template !== null) {
            $this->tour->invoice_footer = $template->content;
            $this->updateValue('tour.invoice_footer', $template->content);
        }
    }

    private function refreshPaymentDetailsTemplate(): void
    {
        $template = LargeTextTemplate::find($this->paymentTemplate);
        if ($template !== null) {
            $this->tour->payment_details = $template->content;
            $this->updateValue('tour.payment_details', $template->content);
        }
    }
}

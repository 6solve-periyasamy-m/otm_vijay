<?php

namespace App\Http\Livewire\Admin\Tour;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\System\LargeTextTemplate;
use App\Models\Tour\Event;
use App\Models\Tour\Tour;
use Livewire\Component;

class Form extends Component
{
    use SendsEvents;
    use LivewireForm;

    public $manuallySet = [];
    /** @var Tour */
    public Tour|int|null $tour = null;
    public int|null $termsTemplate = null;
    public int|null $footerTemplate = null;

    public function mount(Tour|int|null $tour = null): void
    {
        if (is_int($tour)) { $tour = Tour::find($tour); }
        if ($tour === null) {
            $tour = new Tour();
            if (setting('system.installments.deposit', null) !== null) {
                $tour->deposit = setting('system.installments.deposit', null);
                $tour->is_deposit_percentage = true;
            }
        }
        $this->tour = $tour;

        if ($this->tour->date_from !== null) $this->manuallySet('tour.date_from');
        if ($this->tour->date_to !== null) $this->manuallySet('tour.date_to');
        if ($this->tour->final_payment !== null) $this->manuallySet('tour.final_payment');
    }


    public function inputChanged(string|null $key = null): void
    {
        $this->manuallySet($key);
        match ($key) {
            default => function () {},
            "tour.event_id" => $this->eventChanged(),
            "tour.brand_id" => function () { if ($this->tour->brand_id === -1) $this->tour->brand_id = null; },
            "tour.atol_protection" => function () {
                $this->tour->atol_protected = $this->tour->atol_protected === -1
                    ? null : $this->tour->atol_protected;
            },
            'termsTemplate' => $this->refreshTermsTemplate(),
            'footerTemplate' => $this->refreshFooterTemplate(),
        };
    }

    public function manuallySet($key): void
    {
        if (in_array($key, $this->manuallySet)) return;
        $this->manuallySet[] = $key;
    }

    private function eventChanged(): void
    {
        if (in_array("tour.date_from", $this->manuallySet) || in_array("tour.date_to", $this->manuallySet)) return;
        $event = Event::find($this->tour->event_id);
        $this->tour->date_from = $event?->starts_at;
        $this->tour->date_to = $event?->ends_at;
        $this->dateFromChanged();
    }

    private function dateFromChanged(): void
    {
        if (in_array("tour.final_payment", $this->manuallySet)) return;
        $final = setting('system.installments.final', null);
        if ($final !== null) {
            $this->tour->final_payment = $this->tour->date_from->subDays($final);
        }
    }

    public function render()
    {
        return view('livewire.admin.tour.form');
    }

    public function prepareForSaving(): void
    {
        if ($this->tour->brand_id <= 0) $this->tour->brand_id = null;
        $this->tour->is_active = $this->tour->is_active ?? false;
        $this->tour->is_deposit_percentage = $this->tour->is_deposit_percentage ?? false;
        $this->tour->booking_fee = $this->tour->booking_fee ?? 0;
        $this->tour->atol_protected = $this->tour->atol_protected === -1 ? null : $this->tour->atol_protected;
        $this->tour->stock_control_active = $this->tour->stock_control_active ?? false;
        $this->tour->accommodation_stock_control = $this->tour->accommodation_stock_control ?? false;
        $this->tour->activity_stock_control = $this->tour->activity_stock_control ?? false;
        $this->tour->flight_stock_control = $this->tour->flight_stock_control ?? false;
        $this->tour->transport_stock_control = $this->tour->transport_stock_control ?? false;
        $this->tour->merchandise_stock_control = $this->tour->merchandise_stock_control ?? false;
    }

    public function save(): void
    {
        $this->prepareForSaving();
        $this->validate();
        $create = $this->tour->id === null;
        $this->tour->save();
        if ($create) { $this->tour->repository->cloneFromDefaultInstallments(); }
        $this->redirect(route('tours.view', ['tour' => $this->tour,]));
    }

    public function rules(): array
    {
        return [
            'tour.event_id' => 'nullable|int|exists:events,id',
            'tour.name' => 'required|string|min:3',
            'tour.brand_id' => 'nullable|int',
            'tour.tax_bracket_id' => 'nullable|int',
            'tour.description' => 'nullable|string',
            'tour.atol_protected' => 'nullable|int',
            'tour.tour_category_id' => 'nullable|int',
            'tour.is_active' => 'nullable|boolean',
            'tour.booking_form_url' => 'nullable|required_if:tour.is_active,true|string',
            'tour.final_payment' => 'required|date|date_format:Y-m-d',
            'tour.date_from' => 'required|date|date_format:Y-m-d',
            'tour.date_to' => 'required|date|date_format:Y-m-d',
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
        ];
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
}

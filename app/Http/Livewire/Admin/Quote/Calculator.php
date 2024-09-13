<?php

namespace App\Http\Livewire\Admin\Quote;

use App\Exceptions\MailDisabledException;
use App\Exceptions\MailFailedException;
use App\Http\Livewire\Abstract\LivewireForm;
use App\Http\Livewire\SendsEvents;
use App\Models\Quote\Quote;
use App\Models\Quote\QuotePricePoint;
use Exception;
use Livewire\Component;

class Calculator extends Component
{
    use SendsEvents;
    use LivewireForm;

    public $listeners = ['refreshLivewireDatatable' => 'calculate','sendEmail' => 'send'];

    public int $paying = 0;
    public int $travelling = 0;
    public float $costToCompany = 0;
    public float $total = 0;
    public float $profit = 0;
    public float $margin = 0;
    public string|float|null $markup = null;
    public float|null $commission = null;
    public float $toBePaid;
    public float|string $marked_up_price = 0;

    public Quote $quote;
    public float|null $taxes = null;

    public function mount(Quote $quote)
    {
        $this->quote = $quote;
        $this->paying = $this->quote->paying ?? 0;
        $this->travelling = $this->quote->travelling ?? 0;
        $this->calculate(false);
    }

    public function calculate(bool $validate = true): void
    {
        if ($validate) {
            $this->validate();
            $this->markup = (float)$this->markup;
        }
        $companyCostTravellers = ($this->paying + $this->travelling + ($this->leadTravelling()));
        $this->costToCompany = $this->quote->repository->getTotalCostToCompany($companyCostTravellers);
        $costPerPerson = $companyCostTravellers > 0 ? sigfig($this->costToCompany / $companyCostTravellers) : 0;
        $this->total = $this->quote->repository->getTotalCost($this->paying + ($this->quote->leadTraveller->paying ? 1 : 0));
        $this->profit = sigfig($this->total - $this->costToCompany);
        $this->margin = $this->costToCompany == 0 ? 100 : sigfig(($this->total / $this->costToCompany) * 100);
        $this->markup = sigfig($this->markup ?? $this->margin - 100, 6);
        $this->marked_up_price = sigfig($costPerPerson + ($costPerPerson * ($this->markup / 100)));
        if ($this->quote->commission !== null) {
            $this->commission = sigfig($this->total * ($this->quote->commission / 100));
        }
        if ($this->quote->taxBracket()?->rate !== null) {
            $this->taxes = sigfig($this->quote->taxBracket()?->calculate($this->total));
        }
        $this->toBePaid = $this->total - ($this->commission ?? 0.0);
        $this->save();
    }

    public function inputChanged(?string $key = null): void
    {
        if ($key === 'marked_up_price') {
            $companyCostTravellers = ($this->paying + $this->travelling + ($this->leadTravelling()));
            $costPerPerson = $companyCostTravellers > 0 ? sigfig($this->costToCompany / $companyCostTravellers) : 0;
            $this->markup = sigfig(((($this->marked_up_price - $costPerPerson)/$costPerPerson) * 100), 6, true);
        }
        $this->calculate();
    }

    public function incrementPaying(int $value): void
    {
        $this->paying += $value;
        $this->refresh();
    }

    public function incrementTravelling(int $value): void
    {
        $this->travelling += $value;
        $this->refresh();
    }

    public function refresh(): void
    {
        $this->calculate();
        $this->refreshTables();
        $this->dispatchBrowserEvent('travellersUpdated', [
            'paying' => $this->paying + $this->quote->leadTraveller->paying,
            'travelling' => $this->travelling + $this->quote->leadTraveller->travelling,
        ]);
        $this->render();
    }

    public function render()
    {
        return view('livewire.admin.quote.calculator');
    }

    public function rules(): array
    {
        return ['markup' => 'numeric|required',];
    }

    public function updatePricePoint(bool $all = false): void
    {
        $point = $this->quote->repository->getPricePerPerson(1)
            ?? $this->quote->pricePoints()->save(QuotePricePoint::make(['quantity' => 1, 'price_per_person' => 0]));
        $oldPrice = $point->price_per_person;
        $point->price_per_person = $this->marked_up_price;
        $point->save();
        if ($all) {
            foreach ($this->quote->pricePoints as $point) {
                if ($point->quantity === 1) continue;
                $diff = $oldPrice - $point->price_per_person;
                // Calculate the percentage difference (i.e 10% reduction = 0.9) then multiply by marked up price
                if ($diff == 0) {
                    continue;
                } elseif ($diff > 0) {
                    $diffPercent = 1 - ($diff / $oldPrice);
                } else  {
                    $diffPercent = 1 + (($diff * -1) / $oldPrice);
                }
                $point->price_per_person = ($this->marked_up_price * $diffPercent);
                $point->save();
            }
        }
        $this->refresh();
    }

    public function preview()
    {
        $paying = $this->paying + ($this->leadPaying() ? 1 : 0);
        if ($this->quote->repository->getPricePerPerson($paying) === null) {
            $this->toast('Failed to Send Quote', "No price point exists for $paying paying travellers", 'danger');
            return;
        }
        $this->openInNewTab(url()->route('quotes.preview', $this->getUrlArray()));
    }

    public function send()
    {
        $paying = $this->paying + ($this->leadPaying() ? 1 : 0);
        if ($this->quote->repository->getPricePerPerson($paying) === null) {
            $this->toast('Failed to Send Quote', "No price point exists for $paying paying travellers", 'danger');
            return;
        }
        try {
            $status = $this->quote->repository->resend($this->quote->repository->generateSent($this->quote->leadTraveller->email, $this->paying, $this->travelling));
        } catch (MailDisabledException) {
            $this->toast('Failed to Send Quote', 'Emails are not enabled on this system', 'danger');
            return;
        } catch (MailFailedException $e) {
            $status = false;
        }
        if ($status ?? false) {
            $this->toast('Email Sent Successfully', 'The quote document has been successfully sent to the recipient', 'success');
        } else {
            $this->toast('Email Failed to Send', 'The quote document could not be sent to the recipient. Please double check the recipient email address and try again later.', 'danger');
        }
    }

    public function convert()
    {
        return redirect()->route('quotes.conversion', $this->getUrlArray());
    }

    public function costs()
    {
        return redirect()->route('quotes.costing', $this->getUrlArray());
    }

    private function getUrlArray(): array
    {
        return ['quote' => $this->quote, 'paying' => $this->paying, 'travelling' => $this->travelling,];
    }

    private function leadTravelling(): bool
    {
        return $this->quote->leadTraveller->travelling;
    }

    private function leadPaying(): bool
    {
        return $this->quote->leadTraveller->paying;
    }

    private function save(): void
    {
        $this->quote->travelling = $this->travelling;
        $this->quote->paying = $this->paying;
        $this->quote->save();
    }
}

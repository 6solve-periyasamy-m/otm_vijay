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
use Settings;

class Calculator extends Component
{
    use SendsEvents;
    use LivewireForm;

    public $listeners = ['refreshLivewireDatatable' => 'calculate','sendEmail' => 'send'];

    public Quote $quote;

    /** @var string|int $paying The number of paying travellers. Is converted to int when saved */
    public string|int $paying = 0;

    /** @var string|int $travelling The number of non-paying travellers. Is converted to int when saved */
    public string|int $travelling = 0;

    /** @var float $costToCompany Cost of the package to the company */
    public float $costToCompany = 0;

    /** @var float $total Total Cost to Customers */
    public float $total = 0;

    /** @var float|null $profit Raw profit amount (total - cost to company), or null if can't convert */
    public float|null $profit = 0;
    /** @var float $margin Percentage profit margin for package ((total - cost to company) / total)*/
    public float $margin = 0;
    /** @var string|float|null $markup Percentage markup for package ((total - cost to company) / cost to company) */
    public string|float|null $markup = null;
    public float|null $commission = null;
    public bool $adjust = false;
    public float|null $fromRate = null;
    public float|null $toRate = null;
    public float $toBePaid;
    public float|string $marked_up_price = 0;
    public float|null $taxes = null;

    public function mount(Quote $quote)
    {
        $this->quote = $quote;
        $this->paying = $this->quote->paying ?? 0;
        $this->travelling = $this->quote->travelling ?? 0;
        $this->fromRate = $this->quote->from_rate ?? Settings::getConversionRate($this->quote->currency, Settings::currency()) ?? 1;
        $this->toRate = $this->quote->to_rate ?? Settings::getConversionRate(Settings::currency(), $this->quote->currency) ?? 1;
        $this->calculate(false);
    }

    public function calculate(bool $validate = true): void
    {
        if ($validate) {
            $this->validate();
            $this->markup = (float)$this->markup;
            $this->travelling = max($this->quote->travelling_count, (int)$this->travelling);
            $this->paying = max($this->quote->paying_count, (int)$this->paying);
        }

        /** @var int $totalTravellerCount Total number of travellers */
        $totalTravellerCount = ($this->paying + $this->travelling + ($this->leadTravelling()));
        $this->costToCompany = $this->quote->repository->getTotalCostToCompany($totalTravellerCount);

        /** @var float $costPerPerson Cost to the company per person (average) */
        $costPerPerson = $totalTravellerCount > 0 ? sigfig($this->costToCompany / $totalTravellerCount) : 0;
        $paying = $this->paying + ($this->quote->leadTraveller->paying);
        $this->total = ($this->quote->repository->getPricePerPerson($paying)?->price_per_person ?? 0) * $paying;
        if ($this->quote->currency !== null && $this->quote->currency_id !== Settings::currency()?->id) {
            if ($this->fromRate === null) {
                $this->profit = null;
            } else {
                $this->profit = sigfig(sigfig($this->total * $this->fromRate) - $this->costToCompany);
            }
        } else {
            $this->profit = sigfig($this->total - $this->costToCompany);
        }
        $this->margin = $this->total == 0 ? 100 : sigfig(((($this->total * $this->fromRate) - $this->costToCompany) / ($this->total * $this->fromRate)) * 100);

        $this->markup = sigfig($this->markup ?? ($this->costToCompany == 0 ? 100 : (((($this->total * $this->fromRate) - $this->costToCompany) / $this->costToCompany) * 100)), 6);

        $this->marked_up_price = sigfig(($costPerPerson + ($costPerPerson * ($this->markup / 100))));

        $roundValue = (float)setting('round.base_price', null);
        if (!empty($roundValue)) {
            $new = round_to_nearest($this->marked_up_price, $roundValue);
            if ($new !== $this->marked_up_price) {
                $this->marked_up_price = $new;
                $this->toast('Base Price Rounded', "Rounded base price to nearest $roundValue", 'primary');
            }
        }

        if ($this->quote->commission !== null) {
            $this->commission = sigfig($this->total * ($this->quote->commission / 100));
        }

        if ($this->quote->taxBracket()?->rate !== null) {
            $this->taxes = sigfig($this->quote->taxBracket()?->calculate($this->total));
        }

        $this->profit -= $this->commission;
        $this->toBePaid = $this->total - ($this->commission ?? 0.0);
        $this->save();
    }

    public function inputChanged(?string $key = null): void
    {
        $this->validateOnly($key);
        if ($key === 'paying') { $this->paying = (int)$this->paying; }
        if ($key === 'travelling') { $this->travelling = (int)$this->travelling; }
        if ($key === 'marked_up_price') {
            $companyCostTravellers = ($this->paying + $this->travelling + ($this->leadTravelling()));
            $costPerPerson = $companyCostTravellers > 0 ? sigfig($this->costToCompany / $companyCostTravellers) : 0;
            $this->markup = $costPerPerson == 0 ? 100 : sigfig(((($this->marked_up_price - $costPerPerson)/$costPerPerson) * 100), 6, true);
        }
        $this->calculate();
    }

    public function enableEditing(): void
    {
        $this->adjust = true;
        $this->refresh();
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
        $this->emit('updatePaying', $this->paying + $this->quote->leadTraveller->paying);
        $this->dispatchBrowserEvent('travellersUpdated', [
            'paying' => $this->paying + $this->quote->travellers()->where('paying', '=', true)->count(),
            'travelling' => $this->travelling + $this->quote->travellers()->where('travelling', '=', true)->count(),
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
        $this->quote->refresh();
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
                if ($diff === 0) {
                    $multiplier = 1;
                } elseif ($diff > 0) {
                    $multiplier = 1 - ($diff / $oldPrice);
                } else  {
                    $multiplier = 1 + (($diff * -1) / $oldPrice);
                }
                $point->price_per_person = ($this->marked_up_price * $multiplier);
                $point->save();
            }
        }
        $this->refresh();
    }

    public function saveConversion(): void
    {
        $this->quote->from_rate = $this->fromRate;
        $this->quote->to_rate = $this->toRate;
        $this->quote->save();
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
        $target = $this->quote->agent?->email ?? $this->quote->organization?->contact_email ?? $this->quote->leadTraveller->customer->email_address;
        if ($target === null) {
            $this->toast('Failed to Send Quote', 'Cannot send quote, no valid target email found', 'danger');
            return;
        }
        try {
            $status = $this->quote->repository->resend($this->quote->repository->generateSent($target, $this->paying, $this->travelling));
        } catch (MailDisabledException) {
            $this->toast('Failed to Send Quote', 'Emails are not enabled on this system', 'danger');
            return;
        } catch (MailFailedException $e) {
            $status = false;
        } catch (Exception $e) {
            $this->toast('Failed to Send Quote', $e->getMessage(), 'danger');
            return;
        }
        if ($status ?? false) {
            $this->toast('Email Sent Successfully', "The quote document has been successfully sent to the recipient, {$target}", 'success');
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

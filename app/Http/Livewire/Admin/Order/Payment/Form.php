<?php

namespace App\Http\Livewire\Admin\Order\Payment;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Models\Customer\Agent;
use App\Models\Customer\Customer;
use App\Models\Order\Order;
use App\Models\Order\Payment\Payment;
use Livewire\Component;

class Form extends Component
{
    use LivewireForm;

    public Order|int|null $order;
    public Payment|int|null $payment;
    public int|null $customer = null;
    public int|null $agent = null;
    public bool $feeUpdated = false;

    public function mount(Order|int $order, Payment|int|null $payment = null)
    {
        $this->order = Order::getForMount($order);
        $this->payment = Payment::getForMount($payment);
        $this->feeUpdated = $this->payment->id !== null;
        $payer = $this->payment->payer;
        if ($this->payment->id === null) {
            $this->payment->currency_id = $this->order->currency_id;
        }
        if ($payer !== null) {
            if ($payer instanceof Customer) {
                $this->customer = $payer->id;
            } elseif ($payer instanceof Agent) {
                $this->agent = $payer->id;
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.order.payment.form');
    }

    public function updated($name, $value): void
    {
        $this->validateOnly($name);
        if ($name === 'payment.payment_fee') {
            $this->feeUpdated = true;
        }
        if ($name === 'payment.amount' && $this->payment->paymentMethod !== null) {
            $this->updatePaymentFee();
        }
        if ($name === 'payment.payment_method_id' && $this->payment->amount !== null) {
            $this->updatePaymentFee();
        }
    }

    private function updatePaymentFee(): void
    {
        if (!$this->feeUpdated) {
            $method = $this->payment->paymentMethod;
            if ($method?->fee_percentage === null) {
                $this->payment->payment_fee = null;
            } else {
                $this->payment->payment_fee = ($method->fee_percentage / 100) * $this->payment->amount;
            }
        }
    }

    public function deposit(): void
    {
        $this->payment->amount = $this->order->calculated_deposit;
        $this->updatePaymentFee();
    }

    public function next(): void
    {
        $this->payment->amount = $this->order->next_installment?->remaining;
        $this->updatePaymentFee();
    }

    public function remaining(): void
    {
        $this->payment->amount = $this->order->remaining;
        $this->updatePaymentFee();
    }

    public function save()
    {
        $this->validate();
        if ($this->agent !== null) {
            $agent = Agent::find($this->agent);
            if ($agent !== null) {
                $this->payment->payer()->associate($agent);
            }
        }
        if ($this->customer !== null) {
            $customer = Customer::find($this->customer);
            if ($customer !== null) {
                $this->payment->payer()->associate($customer);
            }
        }
        $this->order->payments()->save($this->payment);
        return redirect(route('orders.view', ['order' => $this->order]));
    }

    public function rules()
    {
        return [
            'customer' => 'required_without:agent|nullable|int|exists:customers,id',
            'agent' => 'required_without:customer|nullable|int|exists:agents,id',
            'payment.payment_method_id' => 'required|int|exists:payment_methods,id',
            'payment.amount' => 'required|numeric',
            'payment.payment_fee' => 'nullable|numeric',
            'payment.paid_on' => 'required|date',
            'payment.currency_id' => 'nullable|exists:currencies,id',
        ];
    }
}

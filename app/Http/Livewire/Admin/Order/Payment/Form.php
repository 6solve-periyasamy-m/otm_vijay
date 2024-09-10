<?php

namespace App\Http\Livewire\Admin\Order\Payment;

use App\Http\Livewire\Abstract\LivewireForm;
use App\Models\Order\Order;
use App\Models\Order\Payment\Payment;
use Livewire\Component;

class Form extends Component
{
    use LivewireForm;

    public Order|int|null $order;
    public Payment|int|null $payment;

    public function mount(Order|int $order, Payment|int|null $payment = null)
    {
        $this->order = Order::getForMount($order);
        $this->payment = Payment::getForMount($payment);
    }

    public function render()
    {
        return view('livewire.admin.order.payment.form');
    }

    public function updated($name, $value): void
    {
        $this->validateOnly($name);
    }

    public function deposit(): void
    {
        $this->payment->amount = $this->order->calculated_deposit;
    }

    public function next(): void
    {
        $this->payment->amount = $this->order->next_installment?->amount;
    }

    public function remaining(): void
    {
        $this->payment->amount = $this->order->remaining;
    }

    public function save()
    {
        $this->validate();
        $this->order->payments()->save($this->payment);
        return redirect(route('orders.view', ['order' => $this->order]));
    }

    public function rules()
    {
        return [
            'payment.customer_id' => 'required|int|exists:customers,id',
            'payment.payment_method_id' => 'required|int|exists:payment_methods,id',
            'payment.amount' => 'required|numeric',
            'payment.paid_on' => 'required|date',
        ];
    }
}

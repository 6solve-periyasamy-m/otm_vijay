@php /** @var \App\Models\Order\Order $order */ @endphp

@extends('layout.form', ['action' => route('orders.update', ['order' => $order,]),])

@section('title', 'Update Order')

@section('form-body')
    @include('partials.fields.text', ['name' => 'Deposit', 'field' => 'deposit', 'value' => $order?->deposit, 'width' => 3 ])
    @include('partials.fields.text', ['name' => 'Booking Fee', 'field' => 'booking_fee', 'value' => $order?->booking_fee, 'width' => 3 ])
    @include('partials.fields.datetime', ['name' => 'Ordered On', 'field' => 'ordered_on', 'value' => $order?->ordered_on, 'width' => 3 ])
    @include('partials.fields.dropdown', ['name' => 'Status Override', 'field' => 'status_override', 'width' => 3, 'null' => true, 'values' => \App\Models\Helper\OrderStatus::asArray(), 'selected' => $order?->status_override?->value])
    @include('partials.fields.textarea', ['name' => 'Internal Notes', 'field' => 'internal_notes', 'value' => $order?->internal_notes, 'width' => 6 ])
    @include('partials.fields.textarea', ['name' => 'External Notes', 'field' => 'external_notes', 'value' => $order?->external_notes, 'width' => 6 ])
    <x-livewire.input.select.organization name="organization_id" value="{{ $order?->organization_id }}" label="Organization (Optional)" width="6" />
    <x-livewire.input.select.user name="consultant_id" value="{{ $order?->consultant_id  }}" label="Consultant (Optional)" width="6" nullable="true" clear="true" />
    @include('partials.fields.ckeditor', ['name' => 'Invoice Footer', 'field' => 'invoice_footer', 'value' => $order->invoice_footer, ])
    @include('partials.fields.submit')
@endsection

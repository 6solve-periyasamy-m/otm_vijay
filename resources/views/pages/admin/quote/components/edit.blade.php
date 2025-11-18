@php /** @var \App\Repository\Abstracts\QuoteComponentRepository $quoteComponent */ @endphp

@extends('layout.form', ['action' => $quoteComponent->getUpdateUrl()])

@section('title', 'Edit Quote Component')

@section('form-body')
    <h4 class="fw-bold">{{$quoteComponent->getShortDescription()}}</h4>
    <hr class="splitter">
    <x-admin.input name="sales_price" width="3" value="{{$quoteComponent->getSalesPrice()}}">Sales Price</x-admin.input>
    <x-admin.input name="quantity" width="3" value="{{$quoteComponent->getQuantity()}}">Quantity</x-admin.input>
    <x-admin.input.checkbox name="shown" width="3" value="{{$quoteComponent->priceShown()}}">Show Price On Quote?</x-admin.input.checkbox>
    <x-livewire.input label="Document Order" name="order" value="{{$quoteComponent->get()->document_order}}" width="3" />
    <hr class="splitter">
    <input type="submit" class="btn btn-primary text-white" name="Submit">
@endsection

@php
/**
 * @var \App\Models\Quote\Quote $quote
 * @var int $travelling
 * @var int $paying
 */
use App\Models\Helper\QuoteStatus;
@endphp

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quote - {{ $quote->reference }}</title>
        <link rel="stylesheet" href="{{ asset('css/invoice.css') }}">
        <style>
            .cancelled {
                background-image: url('{{ asset('images/rubberstamp.svg') }}') !important;
                background-repeat: no-repeat !important;
                background-position-x: calc(50% + 3em) !important;
                background-position-y: 8em;
                background-size: 30em;
            }
        </style>
    </head>
    <body>
        <div class="background center-screen @if($quote->status == QuoteStatus::CLOSED || $quote->status == QuoteStatus::EXPIRED) cancelled @endif">
            <!-- Header Section -->
            <div class="section">
                <div class="header">
                    <div class="flex-container titles">
                        <div class="flex-items site-info vert-align">
                            <img src="{{ asset(setting('company.logo', 'images/octlogo.png')) }}" class="header-logo" alt="{{ setting('company.name') }}" />
                        </div>
                        <div class="flex-items vert-align">
                            <h2 class="header-title tour-name">{{ $quote->name }}</h2>
                        </div>
                        <div class="flex-items vert-align">
                            <h1 class="header-title">Quote</h1>
                        </div>
                    </div>
                    <div class="flex-container">
                        <div class="flex-items">
                            <span class="metadata">Website:</span> <a class="site-info-padding" href="{{ setting('company.url', URL::to('/')) }}">{{ setting('company.url', URL::to('/')) }}</a>
                            <br /><span class="metadata">Email:</span> <a class="site-info-padding" href="mailto:{{ setting('company.contact.email', 'Email not set') }}">{{ setting('company.contact.email', 'Email not set') }}</a>
                            <br /><span class="metadata">Telephone:</span> <a class="site-info-padding" href="tel:{{ setting('company.contact.phone', 'Phone number not set') }}">{{ setting('company.contact.phone', 'Phone number not set') }}</a>
                        </div>
                        <div class="flex-items metadata-wrapper">
                            <div class="metadata divider">Paying Travellers<br /><span class="metadata-text">{{ $paying }}</span></div>
                            <div class="metadata divider">Non-Paying Travellers<br /><span class="metadata-text">{{ $travelling }}</span></div>
                            <div class="metadata divider">Quote Ref.<br /><span class="metadata-text">{{ $quote->reference }}</span></div>
                        </div>
                    </div>
                </div>   
            </div>
            <!-- Billing Section -->
            <div class="section">
                <div class="flex-container">
                <div class="flex-items billing-info-wrapper">
                        <div class="metadata divider"><span class="metadata-title">Billing Address</span></div>
                    </div>
                    <div class="flex-items billing-info-wrapper">
                        <div class="metadata divider"><span class="metadata-title">Supplier Address</span></div>
                    </div>
                </div>
                <div class="flex-container">
                    <div class="flex-items billing-info-wrapper">
                        <div class="billing-info">{{ $quote->leadTraveller->name }}</div>
                        <div class="billing-info">{{ $quote->leadTraveller->customer->billingAddress->address_line_1 }}{!! isset($quote->leadTraveller->customer->billingAddress->address_line_1) ? "<br />" : "" !!}</div>
                        <div class="billing-info">{{ $quote->leadTraveller->customer->billingAddress->address_line_2 }}{!! isset($quote->leadTraveller->customer->billingAddress->address_line_2) ? "<br />" : "" !!}</div>
                        <div class="billing-info">{{ $quote->leadTraveller->customer->billingAddress->address_line_3 }}{!! isset($quote->leadTraveller->customer->billingAddress->address_line_3) ? "<br />" : "" !!}</div>
                        <div class="billing-info">{{ $quote->leadTraveller->customer->billingAddress->town }}{!! isset($quote->leadTraveller->customer->billingAddress->town) ? "<br />" : "" !!}</div>
                        <div class="billing-info">{{ $quote->leadTraveller->customer->billingAddress->region }}{!! isset($quote->leadTraveller->customer->billingAddress->region) ? "<br />" : "" !!}</div>
                        <div class="billing-info">{{ $quote->leadTraveller->customer->billingAddress->country }}{!! isset($quote->leadTraveller->customer->billingAddress->country) ? "<br />" : "" !!}</div>
                        <div class="billing-info">{{ $quote->leadTraveller->customer->billingAddress->postcode }}{!! isset($quote->leadTraveller->customer->billingAddress->postcode) ? "<br />" : "" !!}</div>
                    </div>
                    <div class="flex-items billing-info-wrapper">
                        <div class="billing-info">{{ setting('company.address.line_1', 'Company Address Line 1 Not Set') }}</div>
                        <div class="billing-info">{{ setting('company.address.line_2', 'Company Address Line 2 Not Set') }}</div>
                        <div class="billing-info">{{ setting('company.address.city', 'Company City Not Set') }}</div>
                        <div class="billing-info">{{ setting('company.address.region', 'Company Region Not Set') }}</div>
                        <div class="billing-info">{{ setting('company.address.country', 'Company Country Not Set') }}</div>
                        <div class="billing-info">{{ setting('company.address.postcode', 'Company Postcode Not Set') }}</div>
                    </div>
                </div>
            </div>
            <!-- Description Section -->
            <div class="section pagebreak-inside">
                <h2 class="section-title header-title">Description</h2>
                <div class="notes">
                    <div style="margin-top: 0">{!! $quote->description !!}</div>
                </div>
            </div>
            <!-- Order Section -->
            <div class="section">
                <h2 class="section-title header-title">What's Included</h2>
                <table class="order-table center">
                    <thead>
                        <tr>
                            <td class="order-table-title date-double">Dates</td>
                            <td class="order-table-title short-description">Description</td>
                            <td class="order-table-title quantity">Quantity</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="metadata center-text pagebreak">Accommodation</td>
                        </tr>
                        @foreach($quote->repository->getAccommodationForInvoice() as $component)
                            <tr>
                                <td class="date-double"><div class="order-table-description">{{ f_datetime($component->getInventory()->getStartTime()) }} to {{ f_datetime($component->getInventory()->getEndTime()) }}</div></td>
                                <td class="short-description"><div class="order-table-description">{{ $component->getShortDescription() }}</div></td>
                                <td class="quantity">{{ $paying + $travelling }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="metadata center-text pagebreak">Activities</td>
                        </tr>
                        @foreach($quote->repository->getActivitiesForInvoice() as $component)
                            <tr>
                                <td class="date-double"><div class="order-table-description">{{ f_datetime($component->getInventory()->getStartTime()) }} to {{ f_datetime($component->getInventory()->getEndTime()) }}</div></td>
                                <td class="short-description"><div class="order-table-description">{{ $component->getShortDescription() }}</div></td>
                                <td class="quantity">{{ $paying + $travelling }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="metadata center-text pagebreak">Flights</td>
                        </tr>
                        @foreach($quote->repository->getFlightsForInvoice() as $component)
                            <tr>
                                <td class="date-double"><div class="order-table-description">{{ f_datetime($component->getInventory()->getStartTime()) }} to {{ f_datetime($component->getInventory()->getEndTime()) }}</div></td>
                                <td class="short-description"><div class="order-table-description">{{ $component->getShortDescription() }}</div></td>
                                <td class="quantity">{{ $paying + $travelling }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="metadata center-text pagebreak">Transport</td>
                        </tr>
                        @foreach($quote->repository->getTransportForInvoice() as $component)
                            <tr>
                                <td class="date-double"><div class="order-table-description">{{ f_datetime($component->getInventory()->getStartTime()) }} to {{ f_datetime($component->getInventory()->getEndTime()) }}</div></td>
                                <td class="short-description"><div class="order-table-description">{{ $component->getShortDescription() }}</div></td>
                                <td class="quantity">{{ $paying + $travelling }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="metadata right-text">Total: {{ f_currency($quote->repository->getTotalCost($paying)) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="pagebreak"></div>
            <div class="pageborder"></div>
            <!-- Installments Section -->
            <div class="section pagebreak-inside">
                <h2 class="section-title header-title">Installments</h2>
                <table class="order-table center">
                    <thead>
                        <tr>
                            <td class="order-table-title date">Due Date</td>
                            <td class="order-table-title amount">Type</td>
                            <td class="order-table-title paid">Amount</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="date">With Order</td>
                            <td class="amount">Deposit</td>
                            <td class="paid">{{ f_currency($quote->deposit * $paying) }}</td>
                        </tr>
                        @foreach($quote->installments as $installment)
                            <tr>
                                <td class="date">{{ f_date($installment->due_on) }}</td>
                                <td class="amount">Instalment</td>
                                <td class="paid">{{ f_currency($installment->amount * $paying) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="date">{{ f_date($quote->final_payment) }}</td>
                            <td class="amount">Remaining</td>
                            <td class="paid">{{ f_currency($quote->repository->getRemaining($paying)) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="pagebreak"></div>
            <div class="pageborder"></div>
            <!-- Installments Section -->
            <div class="section pagebreak-inside">
                <h2 class="section-title header-title">Price per Traveller Matrix</h2>
                <table class="order-table center">
                    <thead>
                        <tr>
                            <td class="order-table-title date">Active?</td>
                            <td class="order-table-title amount">Paying Travellers</td>
                            <td class="order-table-title paid">Price</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php $pp = $quote->repository->getPricePerPerson($paying); @endphp
                        @foreach($quote->pricePoints as $point)
                            <tr>
                                <td class="date">{{ $point->id == $pp->id ? 'Active' : '' }}</td>
                                <td class="amount">{{ $point->quantity }}</td>
                                <td class="paid">{{ f_currency($point->price_per_person) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Notes Section -->
            <div class="section pagebreak-inside">
                <h2 class="section-title header-title">Notes</h2>
                <div class="notes">
                    <div style="margin-top: 0">{!! $quote->invoice_footer !!}</div>
                </div>
            </div>
            @if((isset($extended) && $extended) || $quote->extended ?? false)
            <div class="section pagebreak-inside">
                <h2 class="section-title header-title">Itinerary</h2>
                <div class="cards">
                    @foreach($quote->repository->getItinerary() as $repository)
                    <div class="card">
                        <div class="item">
                            <img src="{{ $repository->getItineraryAsset() }}" />
                        </div>
                        <div class="item">
                            <div style="display:inline-block">
                                <h4>{{ $repository->getItineraryTitle() }}</h4>
                                {{ $repository->getItineraryDescription() }}
                            </div>
                        </div>
                    </div>
                    <div class="pagebreak"></div>
                    <div class="pageborder"></div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </body>
</html>

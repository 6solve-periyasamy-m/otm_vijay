@php /** @var \App\Models\Quote\Quote $quote */ @endphp
<x-admin.section.header.detail width="6">
    <x-slot:title>{{ __('quotes.view.reference') }}</x-slot:title>
    {{ $quote->ref }}
</x-admin.section.header.detail>

<x-admin.section.header.detail width="6" raw>
    <x-slot:title>{{ __('quotes.view.status') }}</x-slot:title>
    {{ $quote->status->badge() }}
</x-admin.section.header.detail>

<x-admin.section.header.detail width="6">
    <x-slot:title>{{ __('quotes.view.name') }}</x-slot:title>
    {{ $quote->name }}
</x-admin.section.header.detail>

<x-admin.section.header.detail width="6">
    <x-slot:title>{{ __('quotes.view.expires') }}</x-slot:title>
    {{ f_date($quote->expires) }}
</x-admin.section.header.detail>

<x-admin.section.header.detail width="6">
    <x-slot:title>{{ __('quotes.view.starts') }}</x-slot:title>
    {{ f_date($quote->date_from) }}
</x-admin.section.header.detail>

<x-admin.section.header.detail width="6">
    <x-slot:title>{{ __('quotes.view.ends') }}</x-slot:title>
    {{ f_date($quote->date_to) }}
</x-admin.section.header.detail>

<x-admin.section.header.detail width="6">
    <x-slot:title>{{ __('quotes.view.lead.name') }}</x-slot:title>
    {{ $quote->leadTraveller->name }}
</x-admin.section.header.detail>

<x-admin.section.header.detail width="6">
    <x-slot:title>{{ __('quotes.view.lead.contact') }}</x-slot:title>
    <a href="mailto:{{ $quote->leadTraveller->email }}">{{ $quote->leadTraveller->email }}</a>
    (<a href="tel:{{ $quote->leadTraveller->phone }}">{{ $quote->leadTraveller->phone }}</a>)
</x-admin.section.header.detail>

<x-admin.section.header.detail width="6">
    <x-slot:title>{{ __('quotes.view.description') }}</x-slot:title>
    {{ $quote->description }}
</x-admin.section.header.detail>

<x-admin.section.header.detail width="6">
    <x-slot:title>{{ __('quotes.view.notes') }}</x-slot:title>
    {{ $quote->internal_notes }}
</x-admin.section.header.detail>

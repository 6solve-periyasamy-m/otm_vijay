@php /** @var \App\Models\Quote\Quote $quote */ @endphp
<x-admin.section.header.detail width="3">
    <x-slot:title>{{ __('quotes.view.reference') }}</x-slot:title>
    {{ $quote->ref }}
</x-admin.section.header.detail>

<x-admin.section.header.detail width="3">
    <x-slot:title>{{ __('quotes.view.consultant') }}</x-slot:title>
    @if($quote->consultant !== null)
        {{ $quote->consultant->name }} ({{ $quote->consultant->email }})
    @else
        No Consultant
    @endif
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
    {{ $quote->leadTraveller?->name ?? 'Lead Traveller Not Set' }}
</x-admin.section.header.detail>

<x-admin.section.header.detail width="6">
    <x-slot:title>{{ __('quotes.view.lead.contact') }}</x-slot:title>
    <a href="mailto:{{ $quote->leadTraveller?->email }}">{{ $quote->leadTraveller?->email ?? 'No Email Found' }}</a>
    (<a href="tel:{{ $quote->leadTraveller?->phone ?? 'No Telephone Found' }}">{{ $quote->leadTraveller?->phone ?? 'No Telephone Found' }}</a>)
</x-admin.section.header.detail>

<x-admin.section.header.detail width="6">
    <x-slot:title>{{ __('quotes.view.notes.internal') }}</x-slot:title>
    {{ $quote->internal_notes }}
</x-admin.section.header.detail>

<x-admin.section.header.detail width="6">
    <x-slot:title>{{ __('quotes.view.notes.external') }}</x-slot:title>
    {{ $quote->external_notes }}
</x-admin.section.header.detail>

<x-admin.section.header.detail width="12">
    <x-slot:title>{{ __('quotes.view.description') }}</x-slot:title>
    {{ $quote->description }}
</x-admin.section.header.detail>

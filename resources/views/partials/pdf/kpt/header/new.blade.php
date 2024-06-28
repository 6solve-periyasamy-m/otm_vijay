@php /** @var \App\Repository\Storage\Itinerary\Itinerary $itinerary */ @endphp
<table class="new header">
    <!-- Order Information -->
    <tr>
        <td colspan="2" class="v-top text-left">
            <table class="new header-table">
                <tr>
                    <td class="new header-details">
                        <table class="new header-detail-table">
                            <tbody>
                                <tr>
                                    <td class="text-left v-top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="reference">
                                        REFERENCE: {{ $itinerary->reference }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left v-top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="new header-detail-title">
                                        CUSTOMER DETAILS
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="new header-detail-data">
                                        NAME: {{ $itinerary->booker->customer->full_name }}
                                    </td>
                                </tr>
                                @if(!empty($itinerary->booker->customer->mobile_number))
                                <tr>
                                    <td colspan="2" class="new header-detail-data">
                                        PHONE: {{ $itinerary->booker->customer->mobile_number }}
                                    </td>
                                </tr>
                                @endif
                                @if(!empty($itinerary->booker->customer->email_address))
                                <tr>
                                    <td colspan="2" class="new header-detail-data">
                                        EMAIL: <a href="mailto:{{ $itinerary->booker->customer->email_address }}">{{ $itinerary->booker->customer->email_address }}</a>
                                    </td>
                                </tr>
                                @endif
                                @if(!empty($itinerary->organization?->name))
                                    <tr>
                                        <td colspan="2" class="new header-detail-data">
                                            ORGANIZATION: {{ $itinerary->organization?->name }}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="text-left v-top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td class="text-left v-top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="new header-detail-title">
                                        AGENT DETAILS
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="new header-detail-data">
                                        NAME: {{ $itinerary->brand->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="new header-detail-data">
                                        EMAIL:
                                        <a href="mailto:{{ $itinerary->brand->email ?? setting('company.contact.email', 'Email not set') }}">
                                            {{ $itinerary->brand->email ??  setting('company.contact.email', 'Email not set') }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="new header-detail-data">
                                        DATE CREATED: {{ $itinerary->brand->created_at->format('d F Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left v-top">&nbsp;</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="new header-image-td">
                        <table class="new header-image-table">
                            <tr>
                                <td class="text-left v-top">
                                    <img src="{{ $itinerary->image }}" alt="{{ $itinerary->package }}" class="new header-image">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="lower-header-td text-left">
            <table class="lower-header-table">
                <tbody>
                    @if(!empty($itinerary->event))
                        <tr>
                            <td class="lower-header-detail-title">
                                EVENT:
                            </td>
                            <td class="lower-header-detail">
                                {{ $itinerary->event }}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td class="lower-header-detail-title">
                            TRAVEL DATES:
                        </td>
                        <td class="lower-header-detail">
                            {{ $itinerary->start->format('d F Y') }} - {{ $itinerary->end->format('d F Y') }}
                        </td>
                    </tr>
                    @if(!empty($itinerary->package))
                        <tr>
                            <td class="lower-header-detail-title">
                                PACKAGE:
                            </td>
                            <td class="lower-header-detail">
                                {{ $itinerary->package }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </td>
        <td class="lower-header-td text-right">
            <table class="lower-header-table">
                <tbody>
                    <tr>
                        <td class="lower-header-detail-title">
                            TOTAL NUMBER OF PERSONS:
                        </td>
                        <td class="lower-header-detail">
                            {{ $itinerary->getTravellingCount() }} Adult(s)
                        </td>
                    </tr>
                    <tr>
                        <td class="lower-header-detail-title">
                            LEAD GUEST:
                        </td>
                        <td class="lower-header-detail">
                            {{ $itinerary->booker->customer->full_name }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>

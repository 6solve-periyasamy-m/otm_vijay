@php use App\Repository\Storage\Itinerary\Itinerary; @endphp
@php /** @var Itinerary $itinerary */ @endphp
<table class="header">
    <tr>
        <td class="text-left v-top">
            <table class="old header-table">
                <tr>
                    <td class="old header-details">
                        <table class="header-details-table">
                            <tbody>
                                <tr>
                                    <td class="text-left v-top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="header-details-title">
                                        {{ strtoupper($itinerary->package) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left v-top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="header-details-alt">
                                        CUSTOMER DETAILS
                                    </td>
                                </tr>
                                <tr>
                                    <td class="header-details-heading">
                                        LEAD BOOKER:
                                    </td>
                                    <td class="header-details-heading">
                                        {{ $itinerary->booker->full_name }}
                                    </td>
                                </tr>
                                @if(count($itinerary->travellers) > 0)
                                <tr>
                                    <td class="header-details-heading">
                                        OTHER BOOKERS:
                                    </td>
                                    <td class="header-details-alt">
                                        @foreach($itinerary->travellers as $traveller) 
                                            {{ $traveller }}<br />
                                        @endforeach
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="header-details-heading">
                                        BOOKING REFERENCE:
                                    </td>
                                    <td class="header-details-heading">
                                        {{$itinerary->reference}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="header-details-alt">
                                        ONSITE AGENT DETAILS
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="header-details-heading">
                                        NAME: {{$itinerary->booker->title}} {{ $itinerary->booker->first_name . " " . $itinerary->booker->last_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="header-details-heading">
                                        PHONE: {{$itinerary->booker->mobile_number}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="header-details-heading">
                                        EMAIL:
                                        <a href="mailto:{{$itinerary->booker->email_address}}">
                                            {{$itinerary->booker->email_address}}
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="old image-td">
                        <table class="old image-table">
                            <tr>
                                <td class="text-left v-top">
                                    <img src="{{ $itinerary->image }}" class="old image" alt="{{ $itinerary->package }}"/>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

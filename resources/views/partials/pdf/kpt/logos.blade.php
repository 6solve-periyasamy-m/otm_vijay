@php /** @var \App\Repository\Storage\Itinerary\Itinerary $itinerary */ @endphp
<div class="logos">
    <table class="logo-table">
        <tr>
            <td class="text-left v-top">
                <table class="brand-logo-table">
                    <tr>
                        <td>
                            <img src="{{img_to_b64($itinerary->brand->logo)}}" alt="{{ $itinerary->brand->name }}" />
                        </td>
                    </tr>
                </table>
            </td>
            <td class="circle-td">
                <table class="cell-padding-0 document-logo-table debug">
                    <tr>
                        <td class="document-logo">
                            {{ $type }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

@php /** @var Illuminate\Support\Collection|\App\Models\Order\Payment\PaymentIntention[] $data */ @endphp
<table class="table table-striped datatable">
    <thead>
    <tr>
        <th scope="col">Intention ID</th>
        <th scope="col">Customer</th>
        <th scope="col">Related Model</th>
        <th scope="col">Related Reference</th>
        <th scope="col">Booking Reference</th>
        <th scope="col">Processed</th>
        <th scope="col">Amount <abbr title="Some intentions may not have an associated amount">*</abbr></th>
        <th scope="col">Created At</th>
        <th scope="col">Updated At</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $intention)
        <tr>
            <th scope="row">{{ $intention->id }}</th>
            <td>{{ $intention->customer?->full_name ?? "Not Set" }}</td>
            <td>{{ get_class_name($intention->getRelatedModel()) }}</td>
            <td>{{ $intention->reference }}</td>
            <td>
                @if($intention->getRelatedModel() instanceof \App\Models\Order\Order)
                    {{ $intention->reference }}
                @elseif($intention->getRelatedModel() instanceof \App\Models\Booking\Booking)
                    {{ $intention->getRelatedModel()->order?->booking_reference }}
                @elseif($intention->getRelatedModel() instanceof \App\Models\Quote\Quote)
                    {{ $intention->getRelatedModel()->order?->booking_reference }}
                @endif
            </td>
            <td>{{ f_bool($intention->processed) }}</td>
            <td>{{ $intention->amount }}</td>
            <td>{{ f_datetime($intention->created_at) }}</td>
            <td>{{ f_datetime($intention->updated_at) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
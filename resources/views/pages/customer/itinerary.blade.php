@extends('layout.customer')

@section('title', 'View Itinerary')

@push('footer-stack')
    <script>
        const route = "{{ route('customer.itinerary') }}"
        function onOrderChange(selector) {
            window.location = route + '/' + $(selector).val();
        }
    </script>
@endpush
@section('content')
<div class="col-12">
    <form class="form-horizontal mx-2">
        <div class="form-group d-flex align-items-center">
            <p class="mb-0  heading">Select Order</p>
            <select class="form-select order-select" onchange="onOrderChange(this);" id="booking_reference">
                @foreach($orders as $selector)
                    <option value='{{ $selector->booking_reference }}' @if($order->id == $selector->id) selected @endif>{{ $selector->tour->name }} ({{ $selector->booking_reference }})</option>
                @endforeach
            </select>
        </div>
    </form>
</div>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h1 class="border-bottom">Your Itinerary for {{ $order->tour->name }}</h1>
            <div class="col-12">
                <table class="table">
                    <thead>
                    <tr class="font-bold font-20">
                        <td class="w-10">Start Time</td>
                        <td class="w-20">Activity</td>
                        <td class="w-70">Description</td>
                    </tr>
                    </thead>
                    <tbody>
                    @php $day = 1 @endphp
                    @php $previousSlot = null @endphp
                    @foreach($itinerary as $timeslot)
                        @php $currentSlot = $timeslot['start']->copy()->setTime(0, 0, 0) @endphp
                        @if (!isset($previousSlot))
                            <tr class="text-center font-bold bg-gray">
                                <td colspan="3">Day {{ $day }}: {{ StringFormatter::formatDate($currentSlot) }}</td>
                            </tr>
                        @elseif ($previousSlot->diffInDays($currentSlot) >= 1)
                            @php $day += $previousSlot->diffInDays($currentSlot) @endphp
                            <tr class="text-center font-bold bg-gray">
                                <td colspan="3">Day {{ $day }}: {{ StringFormatter::formatDate($currentSlot) }}</td>
                            </tr>
                        @endif
                        <tr class="bg-white">
                            <td>{{ StringFormatter::formatDateTime($timeslot['start']) }}
                                @if(array_key_exists('end', $timeslot))
                                to {{ StringFormatter::formatDateTime($timeslot['end']) }}
                                @endif
                            </td>
                            <td>
                                {{ $timeslot['activity'] }}
                            </td>
                            <td>{{ $timeslot['description'] }}</td>
                        </tr>
                        @php $previousSlot = $currentSlot @endphp
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

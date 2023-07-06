<x-customer.accordion id="costs-collapse" nobg>
    <x-slot:header>
        <h2 class="mb-0" style="width: 100%; text-align: center;">Costs Breakdown</h2>
    </x-slot:header>

    <table class="table table-striped text-center table-mobile-sided">
        <thead>
        <tr>
            <th class="w-80" scope="col">Description</th>
            <th scope="col">Cost</th>
        </tr>
        </thead>
        <tbody>
        @foreach($booking->repository->getBreakdown() as $breakdown)
            <tr>
                <td class="fw-bold" data-content="Description">{{ $breakdown['name'] }}</td>
                <td class="fw-bold" data-content="Cost">{{ f_currency($breakdown['cost']) }}</td>
            </tr>
            @foreach($breakdown['extras'] as $extra)
                <tr>
                    <td data-content="Description">{{ $extra['name'] }}</td>
                    <td data-content="Cost">{{ f_currency($extra['cost']) }}</td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
    <div class="fw-bold font-16 float-end">
        Total: {{ f_currency($booking->total_cost) }}
    </div>
</x-customer.accordion>
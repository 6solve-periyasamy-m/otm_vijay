<x-admin.section.card>
    <table class="datatable table table-striped" id="report">
        <thead>
        <tr>
            @foreach($header as $item)
                <th scope="col">{{ $item }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($data as $dataset)
            <tr>
                @foreach($dataset as $field)
                    <td>{{ $field ?? 'Not Set' }}</td>
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
</x-admin.section.card>

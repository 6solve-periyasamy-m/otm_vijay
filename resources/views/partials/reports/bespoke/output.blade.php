@push('header-stack')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#report').DataTable({fixedHeader: true,});
        });
    </script>
@endpush

<div class="card">
    <div class="card-body">
        <table class="table table-striped" id="report">
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
    </div>
</div>

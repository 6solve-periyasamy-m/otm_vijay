<table class="datatable table table-striped report-table" >
    <thead wire:ignore>
        <tr>
            <th scope="col"><strong>Created</strong></th>
            <th scope="col"><strong>Purchaser Name</strong></th>
            <th scope="col"><strong>Notification Type</strong></th>
            <th scope="col"><strong>Details</strong></th>
            <th scope="col"><strong>Order Reference</strong></th>
            <th scope="col"><strong>Event Name</strong></th>
            <th scope="col"><strong>Package Name</strong></th>
            <th scope="col"><strong>No. of Travellers</strong></th>
            <th scope="col"><strong>Order Value</strong></th>
            <th scope="col"><strong>Purchaser Email</strong></th>
            <th scope="col"><strong>Purchaser Contact</strong></th>
            <th scope="col"><strong>Seen</strong></th>
            <th scope="col"><strong>Resolved by</strong></th>
            <th scope="col"><strong>Action</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <th scope="row">{{ $row->created_at }}</th>
                <td>{{ $row->firstName.' '.$row->lastName }}</td>
                <td>{{ $row->type }}</td>
                <td>{{ $row->details }}</td>
                <td>{!! $row->subject !!}</td>                
                <td>{{ $row->eventName }}</td>
                <td>{{ $row->PackageName }}</td>
                <td>{{ $row->noOfTravellers }}</td>
                <td>{{ $row->totalOrderValue }}</td>
                <td>{{ $row->email }}</td>
                <td>{{ $row->contact }}</td>
                <td>
                    @php $active = $row->seen == 1 ? 'text-green-600' : 'text-red-600' @endphp
                    <div>
                        <svg class="h-5 w-5 stroke-current {{ $active }} mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </td>
                <td>{{ $row->resolved_by? $row->resolved_by : 'Unresolved' }}</td>
                <td>
                    @php $activeResolved = $row->resolved_by != '' ? 'btn-outline-danger' : 'btn-outline-success' @endphp
                    <div>
                        <button wire:click="seen({{ $row->id }})" class="btn btn-outline-info btn-sm mb-1" title="Mark Seen">
                            <div class="d-inline-flex align-content-center justify-content-center" style="width: 16px; height: 16px; font-size: 16px;">
                                <i class="fas fa-eye"></i>
                            </div>
                        </button>
                        <button wire:click="resolve({{ $row->id }})" class="btn {{$activeResolved}} btn-sm mb-1" title="Mark Resolved">
                            <div class="d-inline-flex align-content-center justify-content-center" style="width: 16px; height: 16px; font-size: 16px;">
                                <i class="fas fa-check"></i>
                            </div>
                        </button>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
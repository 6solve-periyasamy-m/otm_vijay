@php
/**
 * @var App\Models\Order\Order[] $orders
 * @var string|null $export
 */
@endphp

@extends('layout.master')

@section('title', 'Bulk Send Reminders')

@push('footer-stack')
<script type="text/javascript">
    let ordersTable = $('.order-table').DataTable({fixedHeader: true,select: { style: "multi+shift" }, });;
    let selectAllToggle = true;
    let sending = false;
    function sendReminders() {
        if (sending) {
            showToast('Cannot Send Reminders', 'Reminders have already been sent!', 'danger');
            return;
        }
        let ids = [];
        ordersTable.rows({ selected: true, }).every((rowIdx, tableLoop, rowLoop) => {
            let row = ordersTable.row(rowIdx);
            ids.push($(row.node()).attr('order_id'));
        });
        if (ids.length > 0) {
            sending = true;
            $.ajax({
                type: "POST",
                url: "{{ route('api.admin.order.reminder.bulk') }}",
                dataType: "json",
                data: { "orders": ids, "__api_token": '{{ Auth::user()->getCurrentToken()->token }}', },
                statusCode: {
                    200: function (data) {
                        if (data.success) {
                            if (data.hasOwnProperty('errors') && data.errors.length > 0) {
                                showToast('Reminders Sent Successfully', 'Reminders have been sent to some selected rows! Please check the errors above', 'warning');
                                for (let key in data.errors) {
                                    displayError(data.errors[key].reference, data.errors[key].reason);
                                }
                                scrollToTop();
                            } else {
                                showToast('Reminders Sent Successfully', 'Reminders have been sent to all selected rows!', 'success');
                            }
                        } else {
                            if (data.hasOwnProperty('errors') && data.errors.length > 0) {
                                showToast('Reminders Failed To Send', 'Please check the errors above!', 'danger');
                                for (let key in data.errors) {
                                    displayError(data.errors[key].reference, data.errors[key].reason);
                                }
                                scrollToTop();
                            }
                        }
                    },
                    400: function (data) {
                        sending = false;
                        showToast('Reminders Failed To Send', 'Something went wrong trying to send the reminders, please try again later', 'danger');
                    },
                    403: function (data) {
                        sending = false;
                        showToast('Access Denied', 'If you believe this is in error, please refresh the page and try again', 'danger');
                    },
                    500: function (data) {
                        sending = false;
                        showToast('An error occurred whilst sending.');
                    }
                },
            });
        } else {
            showToast('Cannot Send Reminders', 'No rows have been selected', 'danger');
        }
    }
    function displayError(reference, error) {
        let message = reference + ": " + error;
        console.log(message);
        showBanner(message, 'danger');
    }
    function selectAll() {
        if (selectAllToggle) {
            ordersTable.rows().select();
        } else {
            ordersTable.rows().deselect();
        }
        selectAllToggle = !selectAllToggle;
    }
</script>
@endpush

@section('content')
    <x-admin.section.card>
        <div class="flex float-end w-100">
            <div>
                <button onclick="selectAll()" class="btn btn-primary">Select All</button>
            </div>
            <div>
                <button onclick="sendReminders()" class="btn btn-warning">{{ Icon::email() }} Send Reminders </button>
            </div>
            @isset($export)
                <div>
                    <a href="{{ $export }}" class="btn btn-info">{{ Icon::excel() }} Export</a>
                </div>
            @endisset
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        <div class="block">
            <div class="block">
                @include('partials.reports.tables.bulk-reminders', ['orders' => $orders,])
            </div>
        </div>
    </x-admin.section.card>
@endsection

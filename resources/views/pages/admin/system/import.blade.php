@extends('layout.master')

@section('title', 'Bulk Importing')

@section('content')
    <x-admin.section.card>
        <div class="d-flex justify-content-start">
            <a href="{{ route('settings.edit') }}" class="btn btn-warning">{{ Icon::back() }} Back to Settings</a>
        </div>
    </x-admin.section.card>
    <x-admin.section.card>
        Please use the templates for all imports. All column headings must be kept in the template, but they can be reordered.
        <br />
        Please verify that all countries are available in the system (we use the ISO country list), as any not found will be skipped and have to be manually set later.
        For example, <span class="fw-bold">England</span>, <span class="fw-bold">Scotland</span>, <span class="fw-bold">Wales</span> and <span class="fw-bold">Northern Island</span> should all be <span class="fw-bold">United Kingdom</span>
        <br />
        All dates should be in the format DD-MM-YYYY, i.e 31-12-2022. Dates with time should be in the format DD-MM-YYYY HH:mm, i.e 31-12-2022 23:45.
        <br />
        All yes/no (boolean) fields should be <span class="fw-bold">YES</span> or <span class="fw-bold">NO</span>
    </x-admin.section.card>
    <div class="imports">
        <div class="row">
            <div class="col-xl-4">
                <x-admin.section.card>
                    <div class="card-title d-flex justify-content-between">
                        <h4 class="fw-bold">Customers</h4>
                        <div>
                            <a class="btn btn-primary pr-2" href="{{ asset('import/customer.csv') }}" target="_blank">
                                Get Template
                            </a>
                            <button class="btn btn-success" onclick="$('#customer-file-upload').click()">
                                Upload File
                            </button>
                        </div>
                    </div>
                    Please make sure that all email addresses are unique, and that all customers have a first and last name.
                    <form action="{{ route('import.customer') }}" enctype="multipart/form-data" method="post" class="d-none">
                        @csrf
                        <input id="customer-file-upload" type="file" name="file" class="d-none" onchange="form.submit()">
                    </form>
                </x-admin.section.card>
            </div>
            <div class="col-xl-4">
                <x-admin.section.card>
                    <div class="card-body">
                        <div class="card-title d-flex justify-content-between">
                            <h4 class="fw-bold">Organization Import</h4>
                            <div>
                                <a class="btn btn-primary pr-2" href="{{ asset('import/organization.csv') }}" target="_blank">
                                    Get Template
                                </a>
                                <button class="btn btn-success" onclick="$('#organization-file-upload').click()">
                                    Upload File
                                </button>
                            </div>
                        </div>
                        Each row must have a name, and if you wish to use with customer import, then the names must be unique.
                        <form action="{{ route('import.organization') }}" enctype="multipart/form-data" method="post" class="d-none">
                            @csrf
                            <input id="organization-file-upload" type="file" name="file" class="d-none" onchange="form.submit()">
                        </form>
                    </div>
                </x-admin.section.card>
            </div>
            <div class="col-xl-4">
                <x-admin.section.card>
                    <div class="card-title d-flex justify-content-between">
                        <h4 class="fw-bold">Accommodation</h4>
                        <div>
                            <a class="btn btn-primary pr-2" href="{{ asset('import/accommodation.csv') }}" target="_blank">
                                Get Template
                            </a>
                            <button class="btn btn-success" onclick="$('#accommodation-file-upload').click()">
                                Upload File
                            </button>
                        </div>
                    </div>
                    If you wish to use Accommodation Inventory importing, please ensure that all Accommodation have unique names, otherwise inventory may be mis-assigned.
                    If required, you can change the name after both imports if you require duplicate names.
                    All accommodation must have a name, and the audit date must be in the correct format where provided.
                    <form action="{{ route('import.accommodation') }}" enctype="multipart/form-data" method="post" class="d-none">
                        @csrf
                        <input id="accommodation-file-upload" type="file" name="file" class="d-none" onchange="form.submit()">
                    </form>
                </x-admin.section.card>
            </div>
            <div class="col-xl-4">
                <x-admin.section.card>
                    <div class="card-title d-flex justify-content-between">
                        <h4 class="fw-bold">Accommodation Inventory</h4>
                        <div>
                            <a class="btn btn-primary pr-2" href="{{ asset('import/accommodation_inventory.csv') }}" target="_blank">
                                Get Template
                            </a>
                            <button class="btn btn-success" onclick="$('#accommodation-inventory-file-upload').click()">
                                Upload File
                            </button>
                        </div>
                    </div>
                    The contents of the Accommodation field must be an exact match to an existing Accommodation Name in the system.
                    Each row must have the following: accommodation, room type, size, check in, check out, stock and purchase price.
                    If sales price is not provided, it will default to the purchase price.
                    Check in and Check out should be in the date-time format, and fit selectable should be in yes/no format.
                    If you wish to use the parent_id field, make sure to have imported the inventory that will be used, and put the ID number of the inventory.
                    You can find the IDs <a href="{{route('accommodation-inventories.identifiers')}}">here</a>.
                    <form action="{{ route('import.accommodation.inventory') }}" enctype="multipart/form-data" method="post" class="d-none">
                        @csrf
                        <input id="accommodation-inventory-file-upload" type="file" name="file" class="d-none" onchange="form.submit()">
                    </form>
                </x-admin.section.card>
            </div>
            <div class="col-xl-4">
                <x-admin.section.card>
                    <div class="card-title d-flex justify-content-between">
                        <h4 class="fw-bold">Activity</h4>
                        <div>
                            <a class="btn btn-primary pr-2" href="{{ asset('import/activity.csv') }}" target="_blank">
                                Get Template
                            </a>
                            <button class="btn btn-success" onclick="$('#activity-file-upload').click()">
                                Upload File
                            </button>
                        </div>
                    </div>
                    If you wish to use Activity Inventory importing, please ensure that all Activity have unique names, otherwise inventory may be mis-assigned.
                    If required, you can change the name after both imports if you require duplicate names.
                    All activity must have a name, and the audit date must be in the correct format where provided.
                    <form action="{{ route('import.activity') }}" enctype="multipart/form-data" method="post" class="d-none">
                        @csrf
                        <input id="activity-file-upload" type="file" name="file" class="d-none" onchange="form.submit()">
                    </form>
                </x-admin.section.card>
            </div>
            <div class="col-xl-4">
                <x-admin.section.card>
                    <div class="card-title d-flex justify-content-between">
                        <h4 class="fw-bold">Activity Inventory</h4>
                        <div>
                            <a class="btn btn-primary pr-2" href="{{ asset('import/activity_inventory.csv') }}" target="_blank">
                                Get Template
                            </a>
                            <button class="btn btn-success" onclick="$('#activity-inventory-file-upload').click()">
                                Upload File
                            </button>
                        </div>
                    </div>
                    The contents of the Activity field must be an exact match to an existing Activity Name in the system.
                    Each row must have the following: activity, ticket type, starts at, ends at, stock and purchase price.
                    If sales price is not provided, it will default to the purchase price.
                    Starts at and ends at should be in the date-time format, and fit selectable should be in yes/no format.
                    <form action="{{ route('import.activity.inventory') }}" enctype="multipart/form-data" method="post" class="d-none">
                        @csrf
                        <input id="activity-inventory-file-upload" type="file" name="file" class="d-none" onchange="form.submit()">
                    </form>
                </x-admin.section.card>
            </div>
            <div class="col-xl-4">
                <x-admin.section.card>
                    <div class="card-title d-flex justify-content-between">
                        <h4 class="fw-bold">Operator</h4>
                        <div>
                            <a class="btn btn-primary pr-2" href="{{ asset('import/operator.csv') }}" target="_blank">
                                Get Template
                            </a>
                            <button class="btn btn-success" onclick="$('#operator-file-upload').click()">
                                Upload File
                            </button>
                        </div>
                    </div>
                    The import must contain exactly one row: name
                    <form action="{{ route('import.operator') }}" enctype="multipart/form-data" method="post" class="d-none">
                        @csrf
                        <input id="operator-file-upload" type="file" name="file" class="d-none" onchange="form.submit()">
                    </form>
                </x-admin.section.card>
            </div>
        </div>
    </div>
@endsection

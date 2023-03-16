<div class="card">
    <div class="card-body">
        Please use the templates for all imports. All column headings must be kept in the template, but they can be reordered.
        <br />
        Please verify that all countries are available in the system (we use the ISO country list), as any not found will be skipped and have to be manually set later.
        For example, <span class="fw-bold">England</span>, <span class="fw-bold">Scotland</span>, <span class="fw-bold">Wales</span> and <span class="fw-bold">Northern Island</span> should all be <span class="fw-bold">United Kingdom</span>
        <br />
        All dates should be in the format DD/MM/YYYY, i.e 31/12/2022. Dates with time should be in the format DD/MM/YYYY HH:mm, i.e 31/12/2022 23:45.
    </div>
</div>
<div class="imports">
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
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
                </div>
            </div>
        </div>
    </div>
</div>

<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Abstract\ImportsToCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System\ImportRequest;
use App\Imports\AccommodationImport;
use App\Imports\AccommodationInventoryImport;
use App\Imports\ActivityImport;
use App\Imports\ActivityInventoryImport;
use App\Imports\ConversionRateImport;
use App\Imports\CustomerImport;
use App\Imports\OperatorImport;
use App\Imports\OrganizationImport;

class ImportController extends Controller
{
    use ImportsToCollection;

    public function customer(ImportRequest $request)
    {
        return $this->import((new CustomerImport()), $request->file);
    }
    public function organization(ImportRequest $request)
    {
        return $this->import((new OrganizationImport()), $request->file);
    }

    public function accommodation(ImportRequest $request)
    {
        return $this->import((new AccommodationImport()), $request->file);
    }

    public function accommodationInventory(ImportRequest $request)
    {
        return $this->import((new AccommodationInventoryImport()), $request->file);
    }

    public function activity(ImportRequest $request)
    {
        return $this->import((new ActivityImport()), $request->file);
    }

    public function activityInventory(ImportRequest $request)
    {
        return $this->import((new ActivityInventoryImport()), $request->file);
    }

    public function operator(ImportRequest $request)
    {
        return $this->import((new OperatorImport()), $request->file);
    }

    public function conversionRate(ImportRequest $request)
    {
        return $this->import((new ConversionRateImport()), $request->file);
    }
}

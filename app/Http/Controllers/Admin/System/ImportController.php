<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System\ImportRequest;
use App\Imports\AccommodationImport;
use App\Imports\AccommodationInventoryImport;
use App\Imports\ActivityImport;
use App\Imports\ActivityInventoryImport;
use App\Imports\CustomerImport;
use Exception;
use Illuminate\Http\UploadedFile;
use Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportController extends Controller
{
    public function customer(ImportRequest $request)
    {
        return $this->import((new CustomerImport()), $request->file);
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

    private function import(ToCollection $import, UploadedFile $file)
    {
        try {
            $import->import($file);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error($e);
            return back()->withErrors(['msg' => 'An error occurred. Please check that the file is a .csv or .xlsx file, all fields are formatted correctly, and try again']);
        }
        return back()->with('success', 'Data imported successfully');
    }
}

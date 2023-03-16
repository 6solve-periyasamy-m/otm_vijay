<?php

namespace App\Http\Controllers\Admin\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\System\ImportRequest;
use App\Imports\CustomerImport;
use Exception;
use Maatwebsite\Excel\Validators\ValidationException;

class ImportController extends Controller
{
    public function customer(ImportRequest $request)
    {
        try {
            (new CustomerImport())->import($request->file);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            return back()->withErrors(['msg' => 'Please check that the file is a .csv or .xlsx file, and try again']);
        }
        return back()->with('success', 'Data imported successfully');
    }
}

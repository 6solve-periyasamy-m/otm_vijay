<?php

namespace App\Http\Controllers\Abstract;

use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Validators\ValidationException;

trait ImportsToCollection
{
    protected function import(ToCollection $import, UploadedFile $file)
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
<?php
namespace App\Imports;

use App\Models\System\ConversionRate;
use App\Models\Location\Currency;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;

class ConversionRateImport implements ToCollection, WithHeadingRow, WithValidation
{
    use Importable;

    public function collection(Collection $collection)
    {
        $currencies = Currency::pluck('id', 'code');

        $data = [];
        foreach ($collection as $row) {
            $from_currency_id = $currencies[$row['from_currency_code']] ?? null;
            $to_currency_id = $currencies[$row['to_currency_code']] ?? null;

            if (!$from_currency_id || !$to_currency_id) {
                continue; 
            }

            $existing_rate = ConversionRate::where('from_currency_id', $from_currency_id)
                ->where('to_currency_id', $to_currency_id)
                ->first();

            if($existing_rate) {
                $existing_rate->update([
                    'rate'       => $row['rate'],
                    'automatic'  => $row['automatic'] ?? false,
                    'changed'    => now(),
                    'updated_at' => now(),
                ]);
            } else {
                ConversionRate::create([
                    'from_currency_id' => $from_currency_id,
                    'to_currency_id'   => $to_currency_id,
                    'rate'             => $row['rate'],
                    'automatic'        => $row['automatic'] ?? false,
                    'changed'          => now(),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'from_currency_code' => ['required', 'exists:currencies,code'],
            'to_currency_code'   => ['required', 'exists:currencies,code'],
            'rate'              => ['required', 'numeric', 'min:0'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'from_currency_code.required' => 'The from currency code is required.',
            'from_currency_code.exists'   => 'Invalid from currency code, not found in the database.',
            'to_currency_code.required'   => 'The to currency code is required.',
            'to_currency_code.exists'     => 'Invalid to currency code, not found in the database.',
            'rate.required'               => 'Exchange rate is required.',
            'rate.numeric'                => 'Exchange rate must be a number.',
            'rate.min'                    => 'Exchange rate cannot be negative.'
        ];
    }
}

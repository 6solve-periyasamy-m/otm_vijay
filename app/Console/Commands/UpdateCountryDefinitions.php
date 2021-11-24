<?php

namespace App\Console\Commands;

use App\Repository\LocationsRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateCountryDefinitions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countries:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update country and currency definitions within the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = Http::get('https://restcountries.com/v3.1/all');
        switch ($response->status()) {
            case 200:
                $this->info('Data has been gathered from the API');
                break;
            default:
                $this->error('Data failed to be gathered. ' . $response->status() . ': ' . $response->body());
                Log::error('Data failed to be gathered. ' . $response->status() . ': ' . $response->body());
                return 1;
        }
        foreach ($response->json() as $data) {
            if (!isset($data['ccn3'])) {
                Log::error($data['name']['common'] . ' has been skipped (No numeric code set)');
                $this->error($data['name']['common'] . ' has been skipped (No numeric code set)');
                continue;
            }
            $prefix = null;
            if (isset($data['idd']['root'])) {
                $prefix = $data['idd']['root'];
                if (isset($data['idd']['suffixes'])) {
                    if (sizeof($data['idd']['suffixes']) == 1) {
                        $prefix .= $data['idd']['suffixes'][0];
                    }
                }
            }
            LocationsRepository::updateCountry($data['ccn3'], $data['cca3'], $data['name']['common'], $prefix, $data['currencies'] ?? []);
            Log::info($data['name']['common'] . ' has been processed');
            $this->info($data['name']['common'] . ' has been processed');
        }
        return 0;
    }
}

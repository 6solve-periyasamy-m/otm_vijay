<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuoteSectionTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exists = DB::table('quote_section_types')->where('name', 'Merchandise')->exists();
        if (!$exists) {
            DB::table('quote_section_types')->insert([
                'name' => 'Merchandise',
                'created_at' => now(),
                'updated_at' => now(),
                'large_text_template_id' => null,
            ]);
        }
    }
}
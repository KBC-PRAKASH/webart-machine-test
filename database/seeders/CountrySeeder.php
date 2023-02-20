<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::truncate();
        $now = date('Y-m-d H:i:s');
        $countryData = [
            [
                'name'       => 'India',
                'status'     => 1,
                'created_at' => $now
            ],[
                'name'       => 'US',
                'status'     => 1,
                'created_at' => $now
            ]
        ];
        Country::insert($countryData);
    }
}

<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::truncate();
        $now = date('Y-m-d H:i:s');
        $cityData = [
            [
                'state_id'      => 1,
                'name'          => 'Kolkata',
                'status'        => 1,
                'created_at'    => $now
            ],
            [
                'state_id'      => 1,
                'name'          => 'Bolpur',
                'status'        => 1,
                'created_at'    => $now
            ],
            [
                'state_id'      => 2,
                'name'          => 'Gorkhpur',
                'status'        => 1,
                'created_at'    => $now
            ],
            [
                'state_id'      => 2,
                'name'          => 'Lucknow',
                'status'        => 1,
                'created_at'    => $now
            ],
            [
                'state_id'      => 3,
                'name'          => 'Raipur',
                'status'        => 1,
                'created_at'    => $now
            ],
            [
                'state_id'      => 3,
                'name'          => 'Bhilai',
                'status'        => 1,
                'created_at'    => $now
            ],
            [
                'state_id'      => 4,
                'name'          => 'Prichard',
                'status'        => 1,
                'created_at'    => $now
            ],
            [
                'state_id'      => 4,
                'name'          => 'Scottsboro',
                'status'        => 1,
                'created_at'    => $now
            ],
        ];

        City::insert($cityData);
    }
}

<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        State::truncate();
        $now = date('Y-m-d H:i:s');
        $stateData = [
            [
                'country_id' => 1,
                'name'       => 'West Bengal',
                'status'     => 1,
                'created_at' => $now
            ],
            [
                'country_id' => 1,
                'name'       => 'Bihar',
                'status'     => 1,
                'created_at' => $now
            ], 
            [
                'country_id' => 1,
                'name'       => 'Chhattisgarh',
                'status'     => 1,
                'created_at' => $now
            ],
            [
                'country_id' => 2,
                'name'       => 'Alabama',
                'status'     => 1,
                'created_at' => $now
            ],

        ];

        State::insert($stateData);

        
    }
}

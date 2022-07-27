<?php

namespace Database\Seeders;

use App\Models\WorkerPosition;
use Illuminate\Database\Seeder;

class WorkerPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        WorkerPosition::insert([
            [ 'built_in' => true, 'name' => 'Carpenter' ],
            [ 'built_in' => true, 'name' => 'Painter' ],
            [ 'built_in' => true, 'name' => 'Mason' ],
            [ 'built_in' => true, 'name' => 'Assistant worker' ],
            [ 'built_in' => true, 'name' => 'Roofer' ],
            [ 'built_in' => true, 'name' => 'Electrician' ],
            [ 'built_in' => true, 'name' => 'Plumbing' ],
            [ 'built_in' => true, 'name' => 'Ventilation' ],
            [ 'built_in' => true, 'name' => 'Project Manager' ],
        ]);
    }
}
